<?php

class ReviewImageModel extends Model {
    protected $table = 'review_images';
    protected $primaryKey = 'id_review_image';

    use StaticInstantiator;

    public function __construct() {
        $this->db = new Database();
    }

    // Create a new review image record
    public function createImage(array $data) {
        $this->db->query("
            INSERT INTO review_images (id_review, image_url) 
            VALUES (:id_review, :image_url)
        ");
        $this->db->bind(':id_review', $data['id_review'], PDO::PARAM_INT);
        $this->db->bind(':image_url', $data['image_url'], PDO::PARAM_STR);
        return $this->db->execute();
    }

    // Retrieve all review images by review ID
    public function getImagesByReviewId(int $idReview) {
        $this->db->query("
            SELECT * 
            FROM review_images 
            WHERE id_review = :id_review
        ");
        $this->db->bind(':id_review', $idReview, PDO::PARAM_INT);
        return $this->db->resultSet();
    }

    // Retrieve a review image by its ID
    public function getImageById(int $id) {
        $this->db->query("
            SELECT * 
            FROM review_images 
            WHERE id_review_image = :id_review_image
        ");
        $this->db->bind(':id_review_image', $id, PDO::PARAM_INT);
        return $this->db->single();
    }

    // Update a review image record
    public function updateImage(int $id, array $data) {
        $this->db->query("
            UPDATE review_images 
            SET id_review = :id_review, 
                image_url = :image_url 
            WHERE id_review_image = :id_review_image
        ");
        $this->db->bind(':id_review_image', $id, PDO::PARAM_INT);
        $this->db->bind(':id_review', $data['id_review'], PDO::PARAM_INT);
        $this->db->bind(':image_url', $data['image_url'], PDO::PARAM_STR);
        return $this->db->execute();
    }

    // Delete a review image record
    public function deleteImage(int $id) {
        $this->db->query("
            DELETE FROM review_images 
            WHERE id_review_image = :id_review_image
        ");
        $this->db->bind(':id_review_image', $id, PDO::PARAM_INT);
        return $this->db->execute();
    }
}

