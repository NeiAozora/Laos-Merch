<?php

class ProfileController extends Controller
{
    private $userModel;

    public function __construct(){
        $this->userModel = new UserModel();
    }

    public function profileSettings(){
        $editMode = false;
        if (isset($_GET["edit"])){
            if (strtolower($_GET["edit"]) == "true"){
                $editMode = true;
            }
        }
        $userData = AuthHelpers::getLoggedInUserData();
        if (is_null($userData)){
            jsRedirect("Laos-Merch/login");
            // throw new ValueError('Userdata of the logged in person are null');
        }
        $this->view("profile/biodata/index" , ["isEditMode"=> $editMode, 'userData' => $userData]);
    }    

    public function updateProfile() {
        // Mengambil data user yang sedang login
        $id_user = $_SESSION['user']['id_user'] ?? null;
        // $userData = AuthHelpers::getLoggedInUserData();
        
        if ($id_user && $_SERVER['REQUEST_METHOD'] == 'POST') {
            // Mendapatkan data dari form
            // $data = [
            //     'id_user' => $_POST['id_user'],  // Menggunakan 'id' dari data yang diambil
            //     'id_firebase' => $_POST['id_firebase'],  // Menggunakan 'uid' dari data yang diambil
            //     'username' => $_POST['username'],
            //     'password' => $_POST['password'],
            //     'first_name' => $_POST['first_name'],
            //     'last_name' => $_POST['last_name'],
            //     'email' => $_POST['email'],
            //     'wa_number' => $_POST['wa_number'],
            //     'id_role' => $_POST['id_role'],  // Asumsikan 'id_role' diambil dari data user yang ada
            //     'profile_picture' => $_POST['profile_picture']
            // ];
            $id_firebase = $_POST['id_firebase'] ?? null;
            $username = $_POST['username'] ?? null;
            // $password = $_POST['password'] ?? null;
            $first_name = $_POST['first_name'] ?? null;
            $last_name = $_POST['last_name'] ?? null;
            $email = $_POST['email'] ?? null;
            $wa_number = $_POST['wa_number'] ?? null;
            // $id_role = $_POST['id_role'] ?? null;
            $profile_picture = $_POST['profile_picture'] ?? null;

            // Melakukan update user
            if($id_user && $id_firebase && $username && $first_name && $last_name && $email && $wa_number && $profile_picture){
                if ($this->userModel->updateUser($id_user, $id_firebase, $username, $first_name, $last_name, $email, $wa_number, $profile_picture)) {
                    header("Location: " . BASEURL . "user/$id_user/profile");
                    exit;
                } else {
                    $_SESSION['error'] = 'error ygy';
                }
            }else{
                $_SESSION['error']  = 'error jg ygy';
            }
        } else {
            $_SESSION['error'] = 'masih error ygy';
        }
        
        header("Location: " . BASEURL . "user/$id_user/profile");
        exit;
    }
}