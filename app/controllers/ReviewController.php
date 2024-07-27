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

    public function getReviewsByProductId($id){
        $reviews = $this->reviewModel->getReviewsByProductId($id);

        header('Content-Type: application/json');

        echo json_encode(["reviews" => $reviews]);

    }
}