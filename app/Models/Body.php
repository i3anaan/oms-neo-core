<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;
use Util;
use Log;
use Auth;
use App\Aspect\Restrict;

use App\Traits\RequiresPermission;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Body extends Model
{
    use RequiresPermission;

    //TODO: Move this down.
    public function getUserPermissions($user) {
        $permissions = collect(["App\Models\Body.address", "App\Models\Body.bodyType", "App\Models\Body.pivot"]);
        if ($user->bodies()->pluck('bodies.id')->contains($this->id)) {
            //If member
            $permissions->push("App\Models\Body.circles");
            $permissions->push("App\Models\Body.users");
            $permissions->push("App\Models\User.circles");
        }
        Log::debug("Found permissions: " . $permissions);
        return $permissions;
    }

    public function getGrantingParents() {
        return [];
    }



    protected $table = "bodies";

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = ['id'];

    public function setNameAttribute($value) {
        $this->attributes['name_simple'] = Util::limitCharacters($value);
        $this->attributes['name'] = $value;
    }


    /**
     * @Restrict
     */
    public function address() {
        return $this->belongsTo('App\Models\Address', 'address_id');
    }

    /**
     * Restrict
     */
    public function users() {
    	return $this->belongsToMany('App\Models\User', 'body_memberships', 'body_id', 'user_id');
    }

    /**
     * @Restrict
     */
    public function bodyType() {
        return $this->belongsTo('App\Models\BodyType', 'type_id');
    }

    /**
     * @Restrict
     */
    public function circles() {
        return $this->hasMany('App\Models\Circle', 'body_id', 'id');
    }

    public function scopeFilterName($query, $name) {
        if (!empty($name)) {
            return $query->where(DB::raw('LOWER(name)'), 'LIKE', "%" . strtolower($name) . "%");
        } else {
            return $query;
        }
    }

    public function scopeFilterCity($query, $city) {
        if (!empty($city)) {
            return $query->select('bodies.*')
            ->rightJoin('addresses', 'bodies.address_id', '=', 'addresses.id')
            ->where(DB::raw('LOWER(addresses.city)'), 'LIKE', "%" . strtolower($city) . "%");
        } else {
            return $query;
        }
    }

    public function scopeFilterCountryID($query, $country_id) {
        if (!empty($country_id)) {
            return $query->select('bodies.*')
            ->rightJoin('addresses', 'bodies.address_id', '=', 'addresses.id')
            ->where('addresses.country_id', $country_id);
        } else {
            return $query;
        }
    }

    public function scopeFilterCountryName($query, $country_name) {
        if (!empty($country_name)) {
            return $query->select('bodies.*')
            ->rightJoin('addresses', 'bodies.address_id', '=', 'addresses.id')
            ->rightJoin('countries', 'addresses.country_id', '=', 'countries.id')
            ->where('countries.name', $country_name);
        } else {
            return $query;
        }
    }

    public function scopeFilterTypeID($query, $type_id) {
        if (!empty($type_id)) {
            return $query->where('type_id', $type_id);
        } else {
            return $query;
        }
    }

    // Model methods go down here..
    public function scopeFilterArray($query, $search = array()) {
        $query->filterName($search['name'] ?? '')
            ->filterCity($search['city'] ?? '')
            ->filterTypeID($search['type_id'] ?? '')
            ->filterCountryID($search['country_id'] ?? '')
            ->filterCountryName($search['country_name'] ?? '');

        return $query;
    }
}
