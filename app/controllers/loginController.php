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
            session_start();
    
            // Verify the Firebase ID token
            $verifiedIdToken = AuthHelpers::verifyFBAcessIdToken($idToken);
            if ($verifiedIdToken) {
                $firebaseId = $verifiedIdToken->claims()->get("sub");
                $email = $verifiedIdToken->claims()->get('email');
                $name = $verifiedIdToken->claims()->get('name');
                $picture = $verifiedIdToken->claims()->get('picture');
                
                // Check if user exists by Firebase ID
                $user = $this->userModel->getUserByFirebaseId($firebaseId);
                if (is_null($user)) {
                    // User does not exist, create a new user
                    $this->userModel->createUser(
                        $firebaseId,
                        $name, // Assuming name is used as username
                        '',    // Placeholder for first_name
                        '',    // Placeholder for last_name
                        $email,
                        '',    // Placeholder for wa_number
                        null   // Placeholder for id_role
                    );
                }
    
                // Set session variables
                $_SESSION['user'] = [
                    'fr' => $verifiedIdToken,
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
    
}