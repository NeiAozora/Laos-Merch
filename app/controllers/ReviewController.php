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
}