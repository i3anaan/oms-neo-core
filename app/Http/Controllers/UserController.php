<?php

namespace App\Http\Controllers;
use Mail;
use Hash;
use File;
use Input;
use Image;
use Excel;
use Session;
use Response;
use App\Http\Requests;
use App\Http\Requests\SaveUserRequest;
use App\Http\Requests\AddBodyToUserRequest;
use App\Models\User;
use App\Models\Role;
use App\Models\Auth;
use App\Models\Country;
use App\Models\UserRole;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function getUsers(User $user, Request $req) {
        $max_permission = $req->get('max_permission');

        // Extract URL arguments to filter on.
        $search = [
            'name'          => $req->name,
            'date_of_birth' => $req->date_of_birth,
            'contact_email' => $req->contact_email,
            'gender'        => $req->gender,
            'status'        => $req->status,
            'body_id'       => $req->body_id,
            'body_name'     => $req->body_name,
            ];

        $users = User::filterArray($search)->get();

        return response()->json($users);
    }

    public function getUser($id) {
        $user = User::findOrFail($id)->with('address', 'bodies')->get();
        return response()->json($user);
    }

    public function getUserByToken() {
        $token = Input::get('token');
        if(empty($token)) {
            $toReturn['success'] = 0;
            return response(json_encode($toReturn), 200);
        }

        $now = date('Y-m-d H:i:s');
        $auth = Auth::where('token_generated', $token)
                        ->where(function($query) use($now) {
                            $query->where('expiration', '>', $now)
                                    ->orWhereNull('expiration');
                        })->firstOrFail();

        return $this->getUser($auth->user_id);
    }

    public function saveUser($id, SaveUserRequest $req) {
        $user = User::findOrFail($id);

        $user->first_name = $req->has('first_name') ? $req->first_name : $user->first_name;
        $user->last_name = $req->has('last_name') ? $req->last_name : $user->last_name;
        $user->date_of_birth = $req->has('date_of_birth') ? $req->date_of_birth : $user->date_of_birth;
        $user->gender = $req->has('gender') ? $req->gender : $user->gender;
        $user->phone = $req->has('phone') ? $req->phone : $user->phone;
        $user->seo_url = $req->has('seo_url') ? $req->seo_url : $user->seo_url;
        $user->password = $req->has('password') ? Hash::make($req->password) : $user->password;
        $user->description = $req->has('description') ? nl2br($req->description) : $user->description;

        $user->contact_email = $req->has('contact_email') ? $req->contact_email : $user->contact_email;
        //TODO Previously there was a hash check as well, but this should already be validated unique by the validation.
        //See SaveUserRequest. Should test if this works properly now.

        $user->address_id = $req->has('address_id') ? Address::findOrFail($req->address_id)->id : $user->address_id;

        $user->save();
        return response()->json($user);
    }

    public function addBodyToUser($user_id, $body_id, AddBodyToUserRequest $req) {
        $membership = BodyMembership::firstOrCreate([
            'user_id'       =>  $user_id,
            'body_id'       =>  $body_id,
        ]);

        $start_date = $req->startInput::get('start_date');
        $end_date = Input::get('end_date');

        $membership->start_date = $req->has('start_date') ? $req->start_date : date('Y-m-d H:i:s');
        $membership->end_date = $req->has('end_date') ? $req->end_date : null;

        $membership->save();

        return response()->json($membership);
    }

    public function activateUser($id, Role $role, Request $req) {
        $currentUser = $req->get('userData');
        $user = User::findOrFail($id);

        if(!empty($user->activated_at)) {
            $toReturn['success'] = 0;
            $toReturn['message'] = "User already activated!";
            return response(json_encode($toReturn), 200);
        }

        $user->seo_url = $user->generateSeoUrl();
        $user->activated_at = date('Y-m-d H:i:s');

        $userPass = $user->generateRandomPassword();

        $oAuthActive = $this->isOauthDefined();
        if($oAuthActive) {
            $domain = $this->getOAuthAllowedDomain();
            $username = $user->seo_url."@".$domain;

            $success = $user->oAuthCreateAccount(
                $this->getOAuthProvider(),
                $this->getDelegatedAdmin(),
                $this->getOauthCredentials($currentUser['id']),
                $domain,
                $user->seo_url,
                $userPass
            );

            $user->internal_email = $username;

            if($success !== true) {
                die("oAuth problem! Error code:".$success);
            }
        } else {
            $username = $user->contact_email;
            $user->password = Hash::make($userPass);
        }

        $user->save();

        $rolesCache = $role->getCache();

        // Now for roles..
        $roles = Input::get('roles', array());
        foreach($roles as $key => $val) {
            if(!$val || !isset($rolesCache[$key])) { // Role set as false or does not exist..
                continue;
            }
            $tmpRole = new UserRole();
            $tmpRole->user_id = $user->id;
            $tmpRole->role_id = $key;
            $tmpRole->save();
        }

        // Email user with all data..
        //TODO

        return response()->json($user);
    }

    public function addUserRoles(Role $role, AddRoleRequest $req) {
        $id = Input::get('user_id');
        $rolesCache = $role->getCache();

        $roles = Input::get('roles');
        foreach ($roles as $key => $val) {
            if(!$val || !isset($rolesCache[$key])) {
                continue;
            }

            UserRole::firstOrCreate([
                'user_id'   =>  $id,
                'role_id'   =>  $key
            ]);
        }

        $toReturn['success'] = 1;
        return response(json_encode($toReturn), 200);
    }

    public function deleteRole(UserRole $obj) {
        $id = Input::get('id');
        $obj = $obj->findOrFail($id);
        $obj->delete();

        $toReturn['success'] = 1;
        return response(json_encode($toReturn), 200);
    }

    public function suspendAccount($id, Request $req) {
        $userData = $req->get('userData');
        $user = $user->findOrFail($id);

        $suspensionReason = $req->reason;
        $user->suspendAccount($userData->id, $suspensionReason);

        return response()->json($user);
    }

    public function unsuspendAccount($id, Request $req) {
        $userData = $req->get('userData');
        $user = $user->findOrFail($id);

        $user->unsuspendAccount($userData->id);

        return response()->json($user);
    }

    public function impersonateUser($id, Request $req) {
        $userData = $req->get('userData');
        $xAuthToken = isset($_SERVER['HTTP_X_AUTH_TOKEN']) ? $_SERVER['HTTP_X_AUTH_TOKEN'] : '';

        $user = $user->findOrFail($id);

        $auth = Auth::where('token_generated', $xAuthToken)->firstOrFail();
        $auth->user_id = $id; // Switching token to new user..
        $auth->save();

        $userData = $user->getLoginUserArray($xAuthToken);

        Session::put('userData', $userData);
        // Mirroring Laravel session data to PHP native session.. For use with angular partials..
        session_start();
        $_SESSION['userData'] = Session::get('userData');
        session_write_close();

        $toReturn['success'] = 1;
        return response(json_encode($toReturn), 200);
    }


    //TODO If I am not mistaken user avatar management can be done a lot simpler using Laravel.
    public function uploadUserAvatar(Request $req) {
        $userData = $req->get('userData');

        $allowedExt = array(
            'png', 'jpg', 'jpeg'
        );

        $path = storage_path();
        $baseDir = $path."/userAvatars/";
        if(!file_exists($baseDir)) {
            mkdir($baseDir, 0777, true);
        }
        $tmpPlace = $path."/userAvatarsTmp/";
        if(!file_exists($tmpPlace)) {
            mkdir($tmpPlace, 0777, true);
        }

        $file = $req->file('avatar');

        $filename = $file->getClientOriginalName();
        $extension = explode('.', $filename);
        $extension = strtolower($extension[count($extension)-1]);
        if(!in_array($extension, $allowedExt)) {
            $toReturn['success'] = 0;
            $toReturn['message'] = "Extension not allowed!";
            return response(json_encode($toReturn), 200);
        }

        $file->move($tmpPlace, $filename);
        $tmpIm = Image::make($tmpPlace.$filename);
        $tmpIm->fit(300);
        $tmpIm->save($baseDir.$userData->id.".jpg");

        unlink($tmpPlace.$filename);

        $toReturn['success'] = 1;
        return response(json_encode($toReturn), 200);
    }

    public function getUserAvatar($avatarId) {
        $fallbackAvatar = storage_path()."/baseFiles/defaultAvatar.jpg";
        $path = storage_path()."/userAvatars/".$avatarId.".jpg";

        if(!File::exists($path)) {
            $file = File::get($fallbackAvatar);
            $type = File::mimeType($fallbackAvatar);
        } else {
            $file = File::get($path);
            $type = File::mimeType($path);
        }

        $response = Response::make($file, 200);
        $response->header("Content-Type", $type);
        return $response;
    }
}
