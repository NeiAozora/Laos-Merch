<?php


use Kreait\Firebase\Factory;
use Kreait\Firebase\Auth;
use Kreait\Firebase\Exception\Auth\FailedToVerifyToken;

class AuthHelpers 
{
    public static function getFirebaseAuth(): Auth {
        static $auth;
        if ($auth === null) {
            $firebase = (new Factory)
            ->withServiceAccount('{"type": "service_account","project_id": "laos-merch","private_key_id": "a2abe512d9918c0fa2bf186a341721b17a6b39a8","private_key": "-----BEGIN PRIVATE KEY-----\nMIIEvQIBADANBgkqhkiG9w0BAQEFAASCBKcwggSjAgEAAoIBAQC+frDcJZuy7kgs\nv98roQJsVeFGCa4fJ2v5zNqaHukoY5ULsdgAo+ggBvQqMn9+nEeaHEeiwfKA4Gzn\nqduq/F5HgO9qDtuGqUUCiYEpSy8fUhT2dRxXp3OV1Lx8PJPkCRVJFfucg/p/3yRO\nIHz9KBzHNlg+cg324j99VzHxAFEFyd4kJfOLyxr1h/LBn5jcd7GtFHjwhJq9CS2b\n4696fDpy6zEZb/z5ZtoK/+GhegWoVnD+5B0NARoR/1/KNHlHhA4N1epy9kKlAoBK\nZd8vHSN1UHwOR/FYsdjFE5r/TfHm8/htV/OaK3stbgl3EsSzDn4HbLBFc4Na1kKP\neeJkZ0tRAgMBAAECggEACq18SWK8RvvPRN7eagok4iCwN2ZMgceKaF6gtDneVNq8\nHIMz+dk2JjFtlz3sYpLs+ojyRP+9EZdC03t0ajGqMTTbdqTtji9HxHwjpsZllkpa\nDXSECa/DrVbW19nnakVOEaIvT9cf//HojY6JowEQF+SGbr2i5b9J0sND89/SKtJM\noqFcN4Ft3I+hsstDbtfBPUSqgv3SbQePiMW4dbl+QNouBaEiJzZkqHwQvLBClcK2\njxYkZXvf/ytKSr4wx3RVFPfAwo7GScOrnL6mCjG+TZDgP6wMUZ3c875uFDWX/Plp\nz05i3df0udwveZyktoiI8Jyh8ZaT4+To3zRG6v704QKBgQDjIk9pEVD00a/7IxhD\nIUa2VqLCkqH2+IwVFQgGd7csu7wsyPKzH1N8gFGgZlmHOLSfIDjnJqhJYjNH/p/m\nzzwI/cEQ8WztWZgYYaU/2GsCdVPrzEz6XpzOtqsRJjhcYTL6GgHCVrHIVKNJ3QMK\ndiQnGX/pBLTums995I6fjodEmQKBgQDWtFhPq7ySOi3f9xrrPXXl1lQuC6E+NQlE\n9vgB1ReiwTkzOlpAGzI2JDkpunKaPYFkeR5w/JfrVuPKKP+ueXVQjJdTOtXTq+/V\nL7Y4Ek/Vb68471gak30Cav9bN3/7/pC/1azNjvXPA4Rf7xKqpnnUZJlbiMQNl3Jr\nCmGwFnM3eQKBgQDUu9/won1Wr8bJyzcAOPyH72EGKFx2epiJbmdT13DC/xriP1vB\nlQeWxoCtYO9djSjvGTjyluxrvwQU3FFb1qF+Ml1Pxy+kppOj0mD6CXNAnC973KAE\np/TqT4Ct0URckdUzFoSrRpPA9DT0C4K7m6eEz4dT8kqcaHcKjLVgkBf1kQKBgHbT\noxic9KV9W4xbp2NQRaLphvdg6mGSWsn3YXUqKYWjKPQoNHDMXHLIg3aLwQeKSMWB\nnw5rTe0qzrFBFZjLkdj9pnai1lrrCrZTTKclw1deE30QQhObUxF2hFNImSWvUw9I\nw/WUTIjA6o9pskwODNk2wAV/4Pmguutw+HXLRwMJAoGAah/5adKLXtOh3OiJsHvg\n/PY6MKsJjs2AiP5+uWua3b8dvk/2vPZkUHrBBb0huOn1j9KjOmknw10qaVbitjcf\nhw11A4KzTf5codkyGIJDWxUfhkA5XlifKNXklXRxX2e9J0Dkqo6GpGHZdiKUYbE4\na/rRkyY1OLKKf8V87f3kXt8=\n-----END PRIVATE KEY-----\n","client_email": "firebase-adminsdk-322og@laos-merch.iam.gserviceaccount.com","client_id": "102955152766839826465","auth_uri": "https://accounts.google.com/o/oauth2/auth","token_uri": "https://oauth2.googleapis.com/token","auth_provider_x509_cert_url": "https://www.googleapis.com/oauth2/v1/certs","client_x509_cert_url": "https://www.googleapis.com/robot/v1/metadata/x509/firebase-adminsdk-322og%40laos-merch.iam.gserviceaccount.com","universe_domain": "googleapis.com"}');
            $auth = $firebase->createAuth();
        }
        return $auth;
    }

    public static function verifyFBAccessIdToken($token) {
        $auth = self::getFirebaseAuth();
        try {
            return $auth->verifyIdToken($token);
        } catch (FailedToVerifyToken $e) {
            // Log the error for debugging
            error_log('Failed to verify token: ' . $e->getMessage());
            return null;
        }
    }


    public static function isLoggedIn(): bool {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        
        if (!array_key_exists("user", $_SESSION)) {
            return false;
        }

        // the approach below may cause a slow down
        if (!array_key_exists("fr", $_SESSION["user"])) {
            return false;
        }

        $idToken = $_SESSION["user"]['fr'];
        $verifiedIdToken = self::verifyFBAccessIdToken($idToken);
        
        if ($verifiedIdToken) {
            $firebaseId = $verifiedIdToken->claims()->get('sub');
            $userModel = new UserModel();
            $user = $userModel->getUserByFirebaseId($firebaseId);

            return $user !== false;
        }

        // the new approach
        if (!array_key_exists("uid", $_SESSION["user"])) {
            return false;
        }

        $firebaseId = $_SESSION["user"]["uid"];
        $userModel = new UserModel();
        $user = $userModel->getUserByFirebaseId($firebaseId);

        return $user !== false;
    }


    public static function getLoggedInUserData(): ?array {

        if (!isset($_SESSION['user'])) {
            return null;
        }

        if (!isset($_SESSION['user']['fr'])) {
            return null;
        }

        $idToken = $_SESSION['user']['fr'];
        $verifiedIdToken = self::verifyFBAccessIdToken($idToken);
        if (is_null($verifiedIdToken)){
            echo `
            <script>
                var baseUrl = "' .BASEURL . '";         
                var do = function () {
                    setTimeout(function () {
                        window.location.reload();
                    }, 5000);
                }

                do()
            </script>
            `;
        }



        if ($verifiedIdToken) {
            $firebaseId = $verifiedIdToken->claims()->get('sub');
            $email = $verifiedIdToken->claims()->get('email');
            $username = $verifiedIdToken->claims()->get('name');
            $picture = $verifiedIdToken->claims()->get('picture');

            $userModel = new UserModel();
            $user = $userModel->getUserByFirebaseId($firebaseId);

            if ($user === null) {
                return null;
            }
            $firstName = $user["first_name"];
            $lastName = $user["last_name"];
            // Return user data
            return [
                'id' => $user["id_user"],
                'fr' => $idToken,
                'uid' => $firebaseId,
                'email' => $email,
                'username' => $user["username"],
                'first_name' => $firstName,
                'last_name' => $lastName,
                'wa_number' => $user['wa_number'],
                'picture' => $user["profile_picture"]
            ];
        }

        return null;
    }

    public static function registerUser($email, $password): string|null {
        $auth = self::getFirebaseAuth();
    
        try {
            $userProperties = [
                'email' => $email,
                'emailVerified' => true,
                'password' => $password,
                'disabled' => false,
            ];
    
            $createdUser = $auth->createUser($userProperties);
    
            // User created successfully, return the user's unique ID
            return $createdUser->uid;
        } catch (\Kreait\Firebase\Exception\Auth\AuthError $e) {
            // Handle error
            echo 'Error creating user: '.$e->getMessage();
            return null;
        }
    }
}
