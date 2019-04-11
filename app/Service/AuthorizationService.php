<?php

namespace App\Service;

use App\Model\Master\AccessControl;

class AuthorizationService
{
    public static function check($resource, $privilege){
        $accessControl = AccessControl::where('username', '=', \Session::get('user')->vc_username)->get();
        foreach ($accessControl as $accessControl) {
            if($accessControl->resources == $resource && $accessControl->access_control == $privilege){
                return TRUE;
            }
        }
        return FALSE;
    }

    public static function canAccess($username, $resource, $privilege){
        $accessControl = AccessControl::where('username', '=', $username)->get();
        foreach ($accessControl as $accessControl) {
            if($accessControl->resources == $resource && $accessControl->access_control == $privilege){
                return TRUE;
            }
        }
        return FALSE;
    }
}
