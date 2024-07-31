<?php

use MailerSend\Helpers\Builder\EmailParams;
use MailerSend\Helpers\Builder\Recipient;

class RegisterController extends Controller
{
    private $userModel;
    private $tempVerificationModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
        $this->tempVerificationModel = new TempVerificationModel();
    }

    public function index()
    {
        $data = $this->getDefaultData();
        $this->view("auth/register/index", $data);
    }

    public function processRegister()
    {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            // Sanitize POST data
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

            $firstName = trim($_POST['firstName']);
            $lastName = trim($_POST['lastName']);
            $email = trim($_POST['email']);
            $username = trim($_POST['username']);
            $password = trim($_POST['password']);
            $confirmPassword = trim($_POST['confirmPassword']);
            $noWA = trim($_POST['noWA']);

            // Validate inputs (basic example, more robust validation is recommended)
            if (empty($firstName) || empty($lastName) || empty($email) || empty($username) || empty($password) || empty($confirmPassword) || empty($noWA)) {
                $this->view("auth/register/index", $this->getDefaultData($firstName, $lastName, $email, $username, $password, $confirmPassword, $noWA, 'Please fill in all fields.'));
                return;
            }

            // Check if user already exists
            $existingUserByEmail = $this->userModel->getUserByCriteria(['email' => $email]);
            $existingUserByUsername = $this->userModel->getUserByCriteria(['username' => $username]);
            $existingUserByWA = $this->userModel->getUserByCriteria(['wa_number' => $noWA]);

            $user = $this->getUser($existingUserByEmail, $existingUserByUsername, $existingUserByWA);
            if (!is_null($user)){
                if (str_contains($user['id_firebase'], 'NOT_VERIFIED_USER')) {
                    $this->prepareVerificationProcedure($user);
                    $this->view("auth/register/index", $this->getDefaultData($firstName, $lastName, $email, $username, $password, $confirmPassword, $noWA, 'User already exists with the provided email, username, or WhatsApp number.'));
                    return;
                }    
            }
            
            // Create a new user with a temporary Firebase ID
            $tempFirebaseId = 'NOT_VERIFIED_USER_' . rand(1000, 9999);
            $profilePicture = null; // Handle profile picture as needed
            $idRole = 2; // Assuming 2 is the default role ID for new users

            if ($this->userModel->createUser($tempFirebaseId, $username, $password, $firstName, $lastName, $email, $noWA, $idRole, $profilePicture) > 0) {
                $user = $this->userModel->getUserByFirebaseId($tempFirebaseId);
                $this->prepareVerificationProcedure($user);
            } else {
                // Handle registration failure
                $this->view("auth/register/index", $this->getDefaultData($firstName, $lastName, $email, $username, $password, $confirmPassword, $noWA, 'Failed to register user. Please try again.'));
            }
        } else {
            $this->index();
        }
    }

    private function getUser($existingUserByEmail, $existingUserByUsername, $existingUserByWA): null | array
    {
        if ($existingUserByEmail) {
            $user = $existingUserByEmail;
        } elseif ($existingUserByUsername) {
            $user = $existingUserByUsername;
        } elseif ($existingUserByWA) {
            $user = $existingUserByWA;
        } else {
            $user = null;
        }

        return $user;
    }

    private function prepareVerificationProcedure(array $user)
    {
        // Generate random verification code and token
        $verificationCode = rand(100000, 999999);
        $tempToken = random_str(); // Ensure this function generates a unique token

        // Create the verification record
        $this->tempVerificationModel->create(
            $user['id_user'],
            $verificationCode,
            $tempToken
        );

        $recipients = [new Recipient($user['email'], 'Recipient')];

        // Start output buffering
        ob_start();

        // Render HTML
        $this->view("auth/email-page/index", [
            'code' => $verificationCode,
            'link' => BASEURL . "auth-verification?ticket=" . $tempToken . "&code=" . $verificationCode,
            'typeOfAction' => "Registration Verification"
        ]);

        // Get the HTML output and clean the buffer
        $htmlContent = ob_get_clean();

        // Use the captured HTML content
        $emailParams = (new EmailParams())
            ->setFrom("laosmerch@trial-neqvygm7e88g0p7w.mlsender.net")
            ->setFromName('Laos-Merch System')
            ->setRecipients($recipients)
            ->setSubject('Registration Verification')
            ->setHtml($htmlContent); // Set the captured HTML content

        MailHelpers::new()->email->send($emailParams);

        jsRedirect("/auth-verification?ticket=" . $tempToken);
    }

    public function verificationIndex()
    {
        if (!isset($_GET["ticket"])) {
            jsRedirect("/login");
            return;
        }

        $token = $_GET["ticket"];
        $tempVerification = $this->tempVerificationModel->getByToken($token);

        if (isNullOrFalse($tempVerification)) {
            jsRedirect("/login");
            return;
        }

        if (isset($_GET["code"])) {
            $this->performValidation($tempVerification, $_GET["code"]);
            return;
        }

        $user = $this->userModel->getUserById($tempVerification["id_user"]);
        
        $this->view("/auth/verification/index", [
            'token' => $tempVerification["temp_token"],
            'email' => $user['email'],
            'failed' => false

        ]);
    }

    public function postPageVerification()
    {

        if (!isset($_POST["token"]) || !isset($_POST["code"])) {
            jsRedirect("/login");
            return;
        }

        $token = $_POST["token"];
        $code = $_POST["code"];
        $tempVerification = $this->tempVerificationModel->getByToken($token);

        if (!$tempVerification) {
            jsRedirect("/login");
            return;
        }

        $this->performValidation($tempVerification, $code);
    }

    private function performValidation($tempVerification, $code)
    {
        if ($tempVerification["code"] == $code) {
            (new TempVerificationModel())->deleteByUser($tempVerification["id_user"]);
            $user = $this->userModel->getUserById($tempVerification["id_user"]);
            
            $uid = AuthHelpers::registerUser($user['email'], $user['password']);

            (new UserModel)->updateUser(
                $user['id_user'], $uid, $user['username'], $user['password'], $user['first_name'], 
                $user['last_name'], $user['email'], $user['wa_number'], $user['id_role'], $user['profile_picture']);

            jsRedirect("/login?verificationSuccess=true");
        } else {
            $user = $this->userModel->getUserById($tempVerification["id_user"]);
            $this->view("/auth/verification/index", [
                
                'token' => $tempVerification["temp_token"],
                'email' => $user['email'],
        
                'failed' => true
    
            ]);
        }
    }

    private function getDefaultData($firstName = '', $lastName = '', $email = '', $username = '', $password = '', $confirmPassword = '', $noWA = '', $modalMessage = '')
    {
        return [
            'firstName' => $firstName,
            'lastName' => $lastName,
            'email' => $email,
            'username' => $username,
            'password' => $password,
            'confirmPassword' => $confirmPassword,
            'noWA' => $noWA,
            'modalMessage' => $modalMessage
        ];
    }
}
