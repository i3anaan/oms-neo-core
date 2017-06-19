<?php

namespace App\Proxies;

use Log;
use App\Proxies\MicrosoftGraphProxy as OBE;

class MailProxy
{
    public function sendLoginDetails($email, $username, $password) {
        $mail =
"Hello,
Hereby your credential information for MyAEGEE - OMS:
    Username: $username
    Password: $password

Please keep this information savely stored.
~The MyAEGEE team
";

        $obe = new OBE();
        $obe->sendEmail($email, "MyAEGEE login information", $mail);
    }
}
