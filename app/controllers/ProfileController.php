<?php

class ProfileController extends Controller
{
    private $userModel;
    private $shippingAddressModel;

    public function __construct(){
        $this->userModel = new UserModel();
        $this->shippingAddressModel = new ShippingAddressModel();
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

        $addresses = $this->shippingAddressModel->getShipAddressByUser($userData['id']);
        
        $this->view("profile/biodata/index" , ["isEditMode"=> $editMode, 'userData' => $userData, 'addresses' => $addresses]);
    }    

   public function updateProfile($id_user)
    {
        $id_user = $_SESSION['user']['id_user'] ?? null;
        if ($id_user && $_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = $_POST['username'] ?? null;
            $first_name = $_POST['first_name'] ?? null;
            $last_name = $_POST['last_name'] ?? null;
            $email = $_POST['email'] ?? null;
            $wa_number = $_POST['wa_number'] ?? null;
           
            $profile_picture = $_FILES['profile_picture']['name'] ?? null;

            if ($profile_picture) {
                $target_dir = BASEURL . "public/storage";
                $target_file = $target_dir . basename($profile_picture);
                move_uploaded_file($_FILES["profile_picture"]["tmp_name"], $target_file);
            }


            $result = $this->userModel->updateUser($id_user,$username,$first_name,$last_name,$email,$wa_number,$profile_picture);

            if ($result) {
                $_SESSION['success'] = 'Berhasil Update.';
            } else {
                $_SESSION['error'] = 'Gagal Update.';
            }

            header("Location: " . BASEURL . "user/" . $id_user . "/profile");
            exit;
        } else {
            $_SESSION['error'] = 'Unauthorized request or invalid request method.';
            header("Location: " . BASEURL . "user/" . $id_user . "/profile");
            exit;
        }
    }


    public function addShippingAddress($id_user){
        $id_user = $_SESSION['user']['id_user'] ?? null;
        if($id_user AND $_SERVER['REQUEST_METHOD'] === 'POST'){
            $label_name = $_POST['label_name'];
            $street_address = $_POST['street_address'];
            $city = $_POST['city'];
            $state = $_POST['state'];
            $postal_code = $_POST['postal_code'];
            $extra_note = $_POST['extra_note'];
            $is_prioritize = 0;
            $is_temporary = 0;
            $result = $this->shippingAddressModel->addShipAddress($id_user,$label_name,$street_address,$city,$state,$postal_code,$extra_note,$is_prioritize,$is_temporary);
            if ($result) {
                $_SESSION['success'] = 'Berhasil Tambah.';
            } else {
                $_SESSION['error'] = 'Gagal Tambah.';
            }

            header("Location: " . BASEURL . "user/" . $id_user . "/profile");
            exit;
        }else{
            $_SESSION['error'] = 'Unauthorized request or invalid request method.';
            header("Location: " . BASEURL . "user/" . $id_user . "/profile");
            exit;
        }
    }

    public function updateShippingAddress($id_user)
    {
        $id_user = $_SESSION['user']['id_user'] ?? null;
        if ($id_user && $_SERVER['REQUEST_METHOD'] === 'POST') {
            $id_shipping_address = $_POST['id_shipping_address'] ?? null;
            $label_name = $_POST['label_name'] ?? null;
            $street_address = $_POST['street_address'] ?? null;
            $city = $_POST['city'] ?? null;
            $state = $_POST['state'] ?? null;
            $postal_code = $_POST['postal_code'] ?? null;
            $extra_note = $_POST['extra_note'] ?? null;

            $result = $this->shippingAddressModel->updateShipAddress($id_user,$id_shipping_address,$label_name,$street_address,$city,$state,$postal_code,$extra_note);

            if ($result) {
                $_SESSION['success'] = 'Berhasil Update.';
            } else {
                $_SESSION['error'] = 'Gagal Update.';
            }

            header("Location: " . BASEURL . "user/" . $id_user . "/profile");
            exit;
        } else {
            $_SESSION['error'] = 'Unauthorized request or invalid request method.';
            header("Location: " . BASEURL . "user/" . $id_user . "/profile");
            exit;
        }
    }

}