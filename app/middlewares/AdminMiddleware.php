<?php

class AdminMiddleware extends Middleware 
{
    public function checkLoginSession()
    {
        if (!array_key_exists("fr", $_SESSION)){
            jsRedirect(BASEURL . "login?message=" . urlencode("Maaf anda diharuskan untuk login!"));
        }

        $accessToken = $_SESSION["fr"];
        $uid = $_SESSION["br"];
        $result = AuthHelpers::verifyFBAccessIdToken($accessToken);
        if (!$result){
            header('Location:' . BASEURL . 'login?message=' . urlencode("Maaf anda diharuskan untuk login!"));
            exit();
        }

        if ($result['role_name'] != 'Admin'){
            header('Location:' . BASEURL . 'login?message=' . urlencode("Anda tidak memiliki hak akses!"));
            exit();
        }
    }


}