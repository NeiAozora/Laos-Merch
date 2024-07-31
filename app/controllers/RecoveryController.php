<?php

use Kreait\Firebase\Exception\FirebaseException;
use MailerSend\Helpers\Builder\EmailParams;
use MailerSend\Helpers\Builder\Recipient;

class RecoveryController extends Controller
{
    private $userModel;
    private $tempVerificationModel;

    public function __construct()
    {
        $this->userModel = new UserModel;
        $this->tempVerificationModel = new TempVerificationModel;
    }

    public function index()
    {
        $this->view("auth/recovery/index", [
            'showAlert' => false,

        ]);
    }

    public function process()
    {
        if (isset($_POST["email"])) {
            $email = $_POST["email"];

            $user = $this->userModel->getUserByCriteria(['email' => $email]);
            if ($user) {
                $id_firebase = $user['id_firebase'];
                $isVerified = !str_contains($id_firebase, 'NOT_VERIFIED_USER');

                if (!$isVerified) {
                    $this->view("auth/recovery/index", [
                        'showAlert' => true,
                        'failed' => true,
                        'message' => 'Akun anda belum terverifikasi! Untuk proses verifikasi silahkan register ulang menggunakan data yang valid, Anda akan diarahkan otomatis ke proses verifikasi.'
                    ]);
                    return;
                }

                if (strlen($user['password']) < 1) {
                    // User uses Google sign-in, send a message indicating they should use Google to reset the password
                    $this->view("auth/recovery/index", [
                        'showAlert' => true,
                        'failed' => true,
                        'message' => 'Akun ini menggunakan Google sign-in. Anda tidak memerlukan proses reset password berikut ini'
                    ]);
                } else {
                    // Start the recovery procedure for email and password sign-in
                    $this->prepareRecoveryProcedure($user);
                    $this->view("auth/recovery/index", [
                        'showAlert' => true,
                        'failed' => false,
                        'message' => 'Instruksi pemulihan telah dikirim ke email Anda.'
                    ]);
                }
            } else {
                $this->view("auth/recovery/index", [
                    'showAlert' => true,
                    'failed' => true,
                    'message' => 'Email tidak ditemukan. Silakan coba lagi.'
                ]);
            }
        } else {
            $this->view("auth/recovery/index", [
                'showAlert' => false,
            ]);
        }
    }


    private function prepareRecoveryProcedure(array $user)
    {
        $firebaseId = $user['id_firebase'];
        $verificationCode = rand(100000, 999999);
        $tempToken = random_str(); // Ensure this function generates a unique token
    
        // Store the temporary verification details in your model/database
        $this->tempVerificationModel->create(
            $user['id_user'],
            $verificationCode,
            $tempToken
        );
    
        try {
            $auth = AuthHelpers::getFirebaseAuth();
            // Retrieve the Firebase user using UID
            $firebaseUser = $auth->getUser($firebaseId);
    
            // Generate a password reset link using the user's email from Firebase
            $link = $auth->getPasswordResetLink($firebaseUser->email);
    
            // Prepare the email recipients and content
            $recipients = [new Recipient($firebaseUser->email, 'Recipient')];
    
            ob_start();
            $this->view("auth/recovery-email/index", [
                'link' => $link, // Use Firebase-generated link
                'typeOfAction' => "Reset Password Verification"
            ]);
            $htmlContent = ob_get_clean();
    
            // Configure email parameters
            $emailParams = (new EmailParams())
                ->setFrom("laosmerch@trial-neqvygm7e88g0p7w.mlsender.net")
                ->setFromName('Laos-Merch System')
                ->setRecipients($recipients)
                ->setSubject('Reset Password Verification')
                ->setHtml($htmlContent);
    
            // Send the email
            MailHelpers::new()->email->send($emailParams);
    
        } catch (FirebaseException $e) {
            // Handle the Firebase exception
            $this->view("auth/recovery/index", [
                'showAlert' => true,
                'failed' => true,
                'message' => 'Gagal untuk megirim email reset, coba lagi lain kali.'
            ]);
        }
    }
    


    
}
