<?php
class ReviewModel {
    private $db;

    public function __construct() {
        $this->db = new Database();
    }

    public function getAllReviews(?int $limit = null) {
        $query = "
                SELECT r.id_review, 
                r.id_variation_combination, 
                r.id_user, 
                u.username, 
                CONCAT(u.first_name, ' ', u.last_name) AS full_name, 
                u.profile_picture, 
                r.rating, 
                r.comment, 
                r.anonymity, 
                r.date_posted, 
                p.product_name,
                vc.id_combination,
                vt.name AS variation_type_name,
                vo.option_name AS variation_name,
                ri.id_review_image,
                ri.image_url
        FROM reviews r
        LEFT JOIN review_images ri ON r.id_review = ri.id_review
        LEFT JOIN users u ON r.id_user = u.id_user
        LEFT JOIN variation_combinations vc ON r.id_variation_combination = vc.id_combination
        LEFT JOIN products p ON vc.id_product = p.id_product
        LEFT JOIN variation_options vo ON vc.id_combination = vo.id_combination
        LEFT JOIN variation_types vt ON vo.id_variation_type = vt.id_variation_type
        WHERE vo.option_name IS NOT NULL
        AND vo.id_option = (
            SELECT MIN(id_option)
            FROM variation_options
            WHERE id_variation_type = vt.id_variation_type
            AND id_combination = vc.id_combination
        )
 
        ";

        if (!is_null($limit)) {
            $query .= " LIMIT :limit";
        }

        $this->db->query($query);

        if (!is_null($limit)) {
            $this->db->bind(':limit', $limit, PDO::PARAM_INT);
        }

        $results = $this->db->resultSet();
        return $this->groupResults($results);
    }

    public function getReviewById(int $id) {
        $this->db->query("
        SELECT r.*, 
        u.username, 
        CONCAT(u.first_name, ' ', u.last_name) as full_name, 
        u.profile_picture, 
        ri.id_review_image, 
        ri.image_url, 
        p.product_name, 
        vc.id_combination,
        vo.option_name as variation_name
            FROM reviews r
            LEFT JOIN review_images ri ON r.id_review = ri.id_review
            LEFT JOIN users u ON r.id_user = u.id_user
            LEFT JOIN variation_combinations vc ON r.id_variation_combination = vc.id_combination
            LEFT JOIN products p ON vc.id_product = p.id_product
            LEFT JOIN variation_options vo ON vc.id_combination = vo.id_combination
            LEFT JOIN variation_types vt ON vo.id_variation_type = vt.id_variation_type
            WHERE r.id_review = :id_review
            group by r.id_variation_combination 
        ");
        $this->db->bind(':id_review', $id, PDO::PARAM_INT);
        $results = $this->db->resultSet();
        return $this->groupResults($results)[0] ?? null;
    }

    public function getReviewsByProductId(int $idProduct) {
        $this->db->query("
            SELECT r.*, u.username, CONCAT(u.first_name, ' ', u.last_name) as full_name, u.profile_picture, 
                   ri.id_review_image, ri.image_url, 
                   p.product_name, vc.id_combination,
                   vo.option_name as variation_name,
                   vt.name AS variation_type_name
            FROM reviews r
            LEFT JOIN review_images ri ON r.id_review = ri.id_review
            LEFT JOIN users u ON r.id_user = u.id_user
            LEFT JOIN variation_combinations vc ON r.id_variation_combination = vc.id_combination
            LEFT JOIN products p ON vc.id_product = p.id_product
            LEFT JOIN variation_options vo ON vc.id_combination = vo.id_combination
            LEFT JOIN variation_types vt ON vo.id_variation_type = vt.id_variation_type
            WHERE vo.option_name IS NOT NULL
            AND vo.id_option = (
                SELECT MIN(id_option)
                FROM variation_options
                WHERE id_variation_type = vt.id_variation_type
                AND id_combination = vc.id_combination
            )
     
            AND p.id_product = :id_product
        ");
        $this->db->bind(':id_product', $idProduct, PDO::PARAM_INT);
        $results = $this->db->resultSet();
        return $this->groupResults($results);
    }

    public function createReview($data) {
        $this->db->query("
            INSERT INTO reviews (id_variation_combination, id_user, rating, comment, anonymity) 
            VALUES (:id_variation_combination, :id_user, :rating, :comment, :anonymity)
        ");
        $this->db->bind(':id_variation_combination', $data['id_variation_combination'], PDO::PARAM_INT);
        $this->db->bind(':id_user', $data['id_user'], PDO::PARAM_INT);
        $this->db->bind(':rating', $data['rating'], PDO::PARAM_INT);
        $this->db->bind(':comment', $data['comment'], PDO::PARAM_STR);
        $this->db->bind(':anonymity', $data['anonymity'], PDO::PARAM_BOOL);
        return $this->db->execute();
    }

    public function deleteReview(int $id) {
        $this->db->query("DELETE FROM reviews WHERE id_review = :id_review");
        $this->db->bind(':id_review', $id, PDO::PARAM_INT);
        return $this->db->execute();
    }

private function groupResults(array $results) {
    $reviews = [];

    foreach ($results as $result) {
        // Initialize review entry if not already set
        if (!isset($reviews[$result['id_review']])) {
            $reviews[$result['id_review']] = [
                'id_review' => $result['id_review'],
                'id_variation_combination' => $result['id_variation_combination'],
                'id_user' => $result['id_user'],
                'username' => $result['username'],
                'full_name' => $result['full_name'],
                'profile_picture' => $result['profile_picture'],
                'rating' => $result['rating'],
                'comment' => $result['comment'],
                'anonymity' => $result['anonymity'],
                'date_posted' => $result['date_posted'],
                'product_name' => $result['product_name'],
                'variation_type_name' => $result['variation_type_name'],
                'variations' => [],
                'images' => []
            ];
        }

        // Collect variation options
        if ($result['variation_name']) {
            $reviews[$result['id_review']]['variations'][] = [
                'variation_type_name' => $result['variation_type_name'],
                'variation_name' => $result['variation_name']
            ];
        }

        // Collect images
        if ($result['id_review_image'] && !isset($reviews[$result['id_review']]['images'][$result['id_review_image']])) {
            $reviews[$result['id_review']]['images'][$result['id_review_image']] = [
                'id_review_image' => $result['id_review_image'],
                'image_url' => $result['image_url']
            ];
        }
    }
    // Flatten the images array to remove the associative array structure
    foreach ($reviews as &$review) {
        $review['images'] = array_values($review['images']);
    
    }

    return array_values($reviews);
}

    
}
