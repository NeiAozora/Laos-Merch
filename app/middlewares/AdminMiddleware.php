<?php

class AdminMiddleware extends Middleware 
{
    public function checkLoginSession()
    {
        if (!isset($_SESSION["user"]['fr'])){
            jsRedirect(BASEURL . "login?message=" . urlencode("Maaf anda diharuskan untuk login!"));
        }

        $accessToken = $_SESSION['user']["fr"];
        $result = AuthHelpers::getLoggedInUserData($accessToken);
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