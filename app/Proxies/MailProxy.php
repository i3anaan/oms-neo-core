<?php

namespace App\Proxies;

use Log;
use App\Proxies\MicrosoftGraphProxy as OBE;

class MailProxy
{
    public function sendLoginDetails($email, $username, $password) {
        Log::debug("Sending login details email...");
        $mail =
"Hello,
Hereby your credential information for MyAEGEE - OMS:
    Username: $username
    Password: $password

Please keep this information savely stored.
~The MyAEGEE team
";

        $obe = new OBE();
        $response = $obe->sendEmail($email, "MyAEGEE login information", $mail);
        Log::debug("Login details email SENT.");
    }
}
