<?php

use Kreait\Firebase\Factory;
use Kreait\Firebase\Auth;

class LoginController extends Controller{

    private UserModel $userModel;
    
    public function __construct()
    {
        $this->userModel = new UserModel();
    }

    public function index(){
        $this->view('auth/login/index');
    }

    public function processAuth()
    {
        $idToken = $_GET["fr"] ?? null;
        
        if ($idToken) {

    
            // Verify the Firebase ID token
            $verifiedIdToken = AuthHelpers::verifyFBAcessIdToken($idToken);
            
            if ($verifiedIdToken) {
                $firebaseId = $verifiedIdToken->claims()->get("sub");
                $email = $verifiedIdToken->claims()->get('email');
                $name = $verifiedIdToken->claims()->get('name');
                $picture = $verifiedIdToken->claims()->get('picture');
                
                // Check if user exists by Firebase ID
                $user = $this->userModel->getUserByFirebaseId($firebaseId);
  
                if (isNullOrFalse($user)) {
                    // User does not exist, create a new user
                    $result = $this->userModel->createUser($firebaseId,$name, '',    '',    $email,'',   2,   $picture
                    );

                }
    
                // Set session variables
                $_SESSION['user'] = [
                    'fr' => $idToken,
                    'uid' => $firebaseId
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
    }

    public function logout(){

        session_unset();
        session_destroy();

        if(!array_key_exists("to", $_GET)){
            jsRedirect(BASEURL);
        }

        $targetUrl = $_GET["to"];
        jsRedirect($targetUrl);


    }
    
}