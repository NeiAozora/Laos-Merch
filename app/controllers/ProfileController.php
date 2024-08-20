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
        // dd($addresses);
        
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


    public function performCRUD() {
    
        // Check for JSON errors
        $data = json_decode(file_get_contents('php://input'), true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            $this->sendError('Invalid JSON format', 400);
        }
    
        // Define required keys for validation
        $requiredKeys = ['addresses', 'token']; // Adjust as needed
    
        // Validate required keys
        foreach ($requiredKeys as $key) {
            if (empty($data[$key])) {
                $this->sendError('Missing required field: ' . $key, 400);
            }
        }
    
        // Verify token
        $verifiedIdToken = AuthHelpers::verifyFBAccessIdToken($data['token']);
        if (empty($verifiedIdToken)) {
            $this->sendError('Invalid or expired token', 403);
        }
    
        $firebaseId = $verifiedIdToken->claims()->get("sub");
        $user = UserModel::new()->getUserByFireBaseId($firebaseId);
        // Get user ID from data
        $id_user = $user['id_user'];
    
        // Instantiate the model
        $shippingAddressModel = new ShippingAddressModel();
    
        // Get current addresses from the database
        $currentAddresses = $shippingAddressModel->getShipAddressByUser($id_user);
        $currentAddressIds = array_column($currentAddresses, 'id_shipping_address');
    
        // Get the posted data
        $postedAddresses = $data['addresses'] ?? [];
    
        // Separate posted data into updates and inserts
        $addressesToUpdate = [];
        $addressesToInsert = [];
        $addressesToDelete = $currentAddressIds;
    
        foreach ($postedAddresses as $address) {
            if (isset($address['id'])) {
                // Update address
                $addressesToUpdate[] = $address;
                // Remove from delete list
                if (($key = array_search($address['id'], $addressesToDelete)) !== false) {
                    unset($addressesToDelete[$key]);
                }
            } else {
                // Insert address
                $addressesToInsert[] = $address;
            }
        }
    
        // Validate address fields for updates and inserts
        foreach (array_merge($addressesToUpdate, $addressesToInsert) as $address) {
            $this->validateAddress($address);
        }
    
        // Perform updates
        foreach ($addressesToUpdate as $address) {
            $shippingAddressModel->updateShipAddress(
                $id_user,
                $address['id'],
                $address['label_name'],
                $address['street_address'],
                $address['city'],
                $address['state'],
                $address['postal_code'],
                $address['extra_note']
            );
        }
    
        // Perform inserts
        foreach ($addressesToInsert as $address) {
            $shippingAddressModel->addShipAddress(
                $id_user,
                $address['label_name'],
                $address['street_address'],
                $address['city'],
                $address['state'],
                $address['postal_code'],
                $address['extra_note'],
                $address['is_prioritize'] ?? false,
                $address['is_temporary'] ?? false
            );
        }
    
        // Perform deletions
        foreach ($addressesToDelete as $addressId) {
            $shippingAddressModel->deleteShipAddress($id_user, $addressId);
        }
    
        // Return a success response
        echo json_encode(['status' => 'success']);
    }
    
    private function validateAddress($address) {
        // Ensure fields are not empty and match the expected types
        if (empty($address['label_name'])) {
            $this->sendError('Missing required field: label_name', 400);
        }
        if (empty($address['street_address'])) {
            $this->sendError('Missing required field: street_address', 400);
        }
        if (empty($address['city'])) {
            $this->sendError('Missing required field: city', 400);
        }
        if (empty($address['state'])) {
            $this->sendError('Missing required field: state', 400);
        }
        if (empty($address['postal_code'])) {
            $this->sendError('Missing required field: postal_code', 400);
        }
    
        // Validate postal_code as a string (or adjust type validation as needed)
        if (!is_string($address['postal_code'])) {
            $this->sendError('Invalid data type for postal_code', 400);
        }
    
        // Additional validation as needed
        // e.g., check that is_prioritize and is_temporary are booleans
        if (isset($address['is_prioritize']) && !is_bool($address['is_prioritize'])) {
            $this->sendError('Invalid data type for is_prioritize', 400);
        }
        if (isset($address['is_temporary']) && !is_bool($address['is_temporary'])) {
            $this->sendError('Invalid data type for is_temporary', 400);
        }
    }
    

    public function updateShippingAddressPriority($id_shipping_address) {
        $user = AuthHelpers::getLoggedInUserData();
        
        if (empty($user)) {
            $this->sendError('Forbidden', 403);
        }

        // Update shipping address priority
        $shippingAddressModel = ShippingAddressModel::new();
        try {
            $result = $shippingAddressModel->updateShippingAddressPriority($user['id'], $id_shipping_address, true);
            if ($result) {
                $this->sendResponse(['message' => 'Shipping address priority updated successfully', 'status' => 200], 200);
            } else {
                $this->sendError('Failed to update priority', 500);
            }
        } catch (Exception $e) {
            $this->sendError($e->getMessage(), 500);
        }
    }

    private function sendError($message, $statusCode) {
        http_response_code($statusCode);
        echo json_encode(['error' => $message]);
        exit;
    }

    private function sendResponse($data, $statusCode) {
        http_response_code($statusCode);
        echo json_encode($data);
        exit;
    }



}