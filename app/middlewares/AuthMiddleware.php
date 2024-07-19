<?php

class AuthMiddleware extends Middleware 
{
    public function checkLoginSession()
    {
        session_start();

        if (!array_key_exists("fr", $_SESSION)){
            jsRedirect(BASEURL . "/login");
        }

        $accessToken = $_SESSION["fr"];
        $uid = $_SESSION["br"];
        if (!AuthHelpers::verifyFBAcessIdToken($accessToken)){
            jsRedirect(BASEURL . "/process-auth?fr=" . $accessToken . "&br=" . $uid);
        }
    }


}