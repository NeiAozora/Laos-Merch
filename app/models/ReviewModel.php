<?php
class ReviewModel extends Model {
    protected $db;
    protected $table = "reviews";
    protected $primaryKey = "id_review";

    use StaticInstantiator;

    public function __construct() {
        $this->db = new Database();
    }

    public function getAllReviews(?int $limit = null) {
        $query = "
            SELECT r.id_review, 
                   r.id_combination,  -- Ensure this column exists and is correct
                   r.id_user, 
                   u.username, 
                   CONCAT(u.first_name, ' ', u.last_name) AS full_name, 
                   u.profile_picture, 
                   r.rating, 
                   r.comment, 
                   r.anonymity, 
                   r.date_posted, 
                   p.product_name,
                   vc.id_combination, -- Ensure this column exists and is correct
                   vt.name AS variation_type_name,
                   vo.option_name AS variation_name,
                   ri.id_review_image,
                   ri.image_url
            FROM reviews r
            LEFT JOIN review_images ri ON r.id_review = ri.id_review
            LEFT JOIN users u ON r.id_user = u.id_user
            LEFT JOIN variation_combinations vc ON r.id_combination = vc.id_combination
            LEFT JOIN combination_details cd ON vc.id_combination = cd.id_combination
            LEFT JOIN variation_options vo ON cd.id_option = vo.id_option
            LEFT JOIN variation_types vt ON vo.id_variation_type = vt.id_variation_type
            LEFT JOIN products p ON vt.id_product = p.id_product
            WHERE vo.option_name IS NOT NULL
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
                   CONCAT(u.first_name, ' ', u.last_name) AS full_name, 
                   u.profile_picture, 
                   ri.id_review_image, 
                   ri.image_url, 
                   p.product_name, 
                   vc.id_combination,  -- Ensure this column exists and is correct
                   vo.option_name AS variation_name
            FROM reviews r
            LEFT JOIN review_images ri ON r.id_review = ri.id_review
            LEFT JOIN users u ON r.id_user = u.id_user
            LEFT JOIN variation_combinations vc ON r.id_combination = vc.id_combination
            LEFT JOIN combination_details cd ON vc.id_combination = cd.id_combination
            LEFT JOIN variation_options vo ON cd.id_option = vo.id_option
            LEFT JOIN products p ON vc.id_product = p.id_product
            WHERE r.id_review = :id_review
        ");
        $this->db->bind(':id_review', $id, PDO::PARAM_INT);
        $results = $this->db->resultSet();
        return $this->groupResults($results)[0] ?? null;
    }

    public function getReviewsByProductId(int $idProduct) {
        $this->db->query("
        SELECT r.id_review, 
                    r.id_combination,  -- Ensure this column exists and is correct
                    r.id_user, 
                    u.username, 
                    CONCAT(u.first_name, ' ', u.last_name) AS full_name, 
                    u.profile_picture, 
                    r.rating, 
                    r.comment, 
                    r.anonymity, 
                    r.date_posted, 
                    p.product_name,
                    vc.id_combination, -- Ensure this column exists and is correct
                    vt.name AS variation_type_name,
                    vo.option_name AS variation_name,
                    ri.id_review_image,
                    ri.image_url
            FROM reviews r
            LEFT JOIN review_images ri ON r.id_review = ri.id_review
            LEFT JOIN users u ON r.id_user = u.id_user
            LEFT JOIN variation_combinations vc ON r.id_combination = vc.id_combination
            LEFT JOIN combination_details cd ON vc.id_combination = cd.id_combination
            LEFT JOIN variation_options vo ON cd.id_option = vo.id_option
            LEFT JOIN variation_types vt ON vo.id_variation_type = vt.id_variation_type
            LEFT JOIN products p ON vt.id_product = p.id_product
            WHERE p.id_product = :id_product
        ");
        $this->db->bind(':id_product', $idProduct, PDO::PARAM_INT);
        $results = $this->db->resultSet();
        return $this->groupResults($results);
    }

    public function createReview($data) {
        $this->db->query("
            INSERT INTO reviews (id_combination, id_user, rating, comment, anonymity) 
            VALUES (:id_combination, :id_user, :rating, :comment, :anonymity)
        ");
        $this->db->bind(':id_combination', $data['id_combination'], PDO::PARAM_INT);
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
                    'id_combination' => $result['id_combination'],  // Adjust to match query results
                    'id_user' => $result['id_user'],
                    'username' => $result['username'],
                    'full_name' => $result['full_name'],
                    'profile_picture' => $result['profile_picture'],
                    'rating' => $result['rating'],
                    'comment' => $result['comment'],
                    'anonymity' => $result['anonymity'],
                    'date_posted' => $result['date_posted'],
                    'product_name' => $result['product_name'],
                    'variations' => [],
                    'images' => []
                ];
            }

            // Collect variation options
            if (isset($result['variation_name'])) {  // Check if the key exists
                $reviews[$result['id_review']]['variations'][] = [
                    'variation_name' => $result['variation_name']
                ];
            }

            // Collect images
            if (isset($result['id_review_image']) && !isset($reviews[$result['id_review']]['images'][$result['id_review_image']])) {
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
