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
        $reviews = $this->reviewModel->getReviewsByProductId((int) $_GET['product']);
    
        // Respond with the reviews in JSON format
        header('Content-Type: application/json');
        http_response_code(200); // 200 OK for successful response
        echo json_encode(["reviews" => $reviews]);
    }
    
}