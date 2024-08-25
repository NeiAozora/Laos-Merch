<?php


class ReviewController {
    private $reviewModel;
    private $reviewImagesModel;
    public function __construct()
    {
        $this->reviewModel = new ReviewModel;
        $this->reviewImagesModel = new ReviewImageModel;
    }

    public function getReviews(){
        $reviews = $this->reviewModel->getAllReviews(5);

        header('Content-Type: application/json');

        echo json_encode(["reviews" => $reviews]);

    }

    public function getReviewsByProductId($id) {
        // Check if 'product' parameter is set in the query string
        if (!isset($id)) {
            // Respond with a 400 Bad Request error if the 'product' parameter is missing
            header('Content-Type: application/json');
            http_response_code(400);
            echo json_encode(["error" => "Product ID is required"]);
            return;
        }
    
        // Validate if 'product' parameter is a valid integer
        if (!(int) $id) {
            // Respond with a 400 Bad Request error if the 'product' parameter is not a valid integer
            header('Content-Type: application/json');
            http_response_code(400);
            echo json_encode(["error" => "Invalid Product ID"]);
            return;
        }
    
        // Fetch reviews using the model
        $reviews = $this->reviewModel->getReviewsByProductId((int) $id);
    
        // Respond with the reviews in JSON format
        header('Content-Type: application/json');
        http_response_code(200); // 200 OK for successful response
        echo json_encode(["reviews" => $reviews]);
    }
    

    public function submitReview() {
        // Define required POST parameters
        $requiredKeys = [
            'rating',
            'id_combination',
            'token',
            'reviewText',
            'id_order_item',
            'anonymity',
        ];
        header('Content-Type: application/json');
    
        // Check if all required parameters are present
        foreach ($requiredKeys as $key) {
            if (empty($_POST[$key])) {
                echo json_encode([
                    'success' => false,
                    'code' => 400,
                    'message' => 'Invalid data',
                    'detailMessage' => "Required Key '$key' is missing"
                ]);
                http_response_code(400);
                exit;
            }
        }
    
        // Retrieve form data
        $rating = $_POST['rating'];
        $reviewText = $_POST['reviewText'];
        $anonymous = isset($_POST['anonymity']) ? 1 : 0;
        $idCombination = $_POST['id_combination'];
        $idOrderItem = $_POST['id_order_item'] ?? null;
        $token = $_POST['token'];
    
        // Verify token
        $verifiedIdToken = AuthHelpers::verifyFBAccessIdToken($token);
    
        if (empty($verifiedIdToken)) {
            echo json_encode([
                'success' => false,
                'code' => 403,
                'message' => 'Forbidden',
                'detailMessage' => 'Invalid token'
            ]);
            http_response_code(403);
            exit;
        }
    
        $firebaseId = $verifiedIdToken->claims()->get("sub");
        $user = UserModel::new()->get(['id_firebase', $firebaseId]);
    
        if (empty($user)) {
            echo json_encode([
                'success' => false,
                'code' => 403,
                'message' => 'Forbidden',
                'detailMessage' => 'User not found'
            ]);
            http_response_code(403);
            exit;
        }
    
        $idUser = $user[0]['id_user'];
    
        // Validate form data
        if (empty($rating) || $idCombination === null || $idUser === null || empty($reviewText)) {
            echo json_encode([
                'success' => false,
                'code' => 400,
                'message' => 'Invalid parameters',
                'detailMessage' => 'Required parameters are missing or invalid'
            ]);
            http_response_code(400);
            exit;
        }
    
        // Create a new review
        $reviewModel = new ReviewModel();
        $data = [
            'id_combination' => $idCombination,
            'id_user' => $idUser,
            'id_order_item' => $idOrderItem, // Optional
            'rating' => (int)$rating,
            'comment' => $reviewText,
            'anonymity' => (bool)$anonymous
        ];
        $reviewId = $reviewModel->createReview($data);
    
        if ($reviewId) {
            // Handle image uploads
            if (isset($_FILES['reviewImages']) && !empty($_FILES['reviewImages'])) {
                $imageModel = new ReviewImageModel();
                foreach ($_FILES['reviewImages']['tmp_name'] as $index => $tmpName) {
                    $imageUrl = $this->uploadImage($tmpName, $_FILES['reviewImages']['name'][$index]);
                    if ($imageUrl) {
                        $imageModel->createImage([
                            'id_review' => $reviewId,
                            'image_url' => $imageUrl
                        ]);
                    }
                }
            }
    
            // Return success response
            echo json_encode([
                'success' => true,
                'code' => 200,
                'message' => 'Review submitted successfully',
                'reviewId' => $reviewId
            ]);
            http_response_code(200);
        } else {
            // Handle review creation failure
            echo json_encode([
                'success' => false,
                'code' => 500,
                'message' => 'Server Error',
                'detailMessage' => 'Unable to submit review. Please try again later.'
            ]);
            http_response_code(500);
        }
    }
    
    

    private function uploadImage($tmpName, $fileName) {
        $uploadDir = ROOT . '/public/storage/review_images/';
        $uploadFile = $uploadDir . basename($fileName);

        if (move_uploaded_file($tmpName, $uploadFile)) {
            return  'public/storage/review_images/' . basename($fileName);;
        } else {
            // Handle upload failure
            return false;
        }
    }

}