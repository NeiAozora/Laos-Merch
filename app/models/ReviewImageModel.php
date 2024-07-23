<?php

class ReviewImageModel {
    private $db;

    public function __construct() {
        $this->db = new Database();
    }

    public function createReviewImage($data) {
        $this->db->query("INSERT INTO review_images (id_review, image_url) VALUES (:id_review, :image_url)");
        $this->db->bind(':id_review', $data['id_review']);
        $this->db->bind(':image_url', $data['image_url']);
        return $this->db->execute();
    }

    public function deleteReviewImage($id) {
        $this->db->query("DELETE FROM review_images WHERE id_review_image = :id_review_image");
        $this->db->bind(':id_review_image', $id);
        return $this->db->execute();
    }
}
?>
