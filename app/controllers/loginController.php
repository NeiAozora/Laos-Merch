<?php

use Kreait\Firebase\Factory;
use Kreait\Firebase\Auth;

class LoginController extends Controller{

    public function index(){
        $this->view('auth/login/index');
    }

    public function processAuth(){
        $idToken = $_GET["fr"];
        if ($idToken) {
            session_start();
        
            $verifiedIdToken = AuthHelpers::verifyFBAcessIdToken($idToken);
            if ($verifiedIdToken) {
                $_SESSION['user'] = [
                    'fr' => $verifiedIdToken,
                    'uid' => $verifiedIdToken->claims()->get('sub'),
                    'email' => $verifiedIdToken->claims()->get('email'),
                    'name' => $verifiedIdToken->claims()->get('name'),
                    'picture' => $verifiedIdToken->claims()->get('picture')
                ];
                echo json_encode(['status' => 'success']);
            } else {
                session_destroy();
                http_response_code(401);
                echo json_encode(['status' => 'error', 'message' => 'Invalid ID token']);
            }
        } else {
            http_response_code(400);
            echo json_encode(['status' => 'error', 'message' => 'ID token is missing']);
        }
        
        // if (is_null($verifiedIdToken)){
        //     // jsRedirect(BASEURL . "login");
        // }

        // dd($verifiedIdToken);

        // $_SESSION['user'] = [
        //     'uid' => $verifiedIdToken->claims()->get('sub'),
        //     'email' => $verifiedIdToken->claims()->get('email'),
        //     'name' => $verifiedIdToken->claims()->get('name'),
        //     'picture' => $verifiedIdToken->claims()->get('picture')
        // ];
       

        // jsRedirect(BASEURL);

    }
}