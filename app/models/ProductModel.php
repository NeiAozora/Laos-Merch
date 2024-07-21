<?php


class ProductModel {
    private $db;

    public function __construct() {
        $this->db = new Database();
    }

    // Create a new product
    public function createProduct($product) {
        $this->db->query('INSERT INTO products (product_name, description, id_category, weight, dimensions, image_url, discontinued) VALUES (:product_name, :description, :id_category, :weight, :dimensions, :image_url, :discontinued)');
        
        // Bind values
        $this->db->bind(':product_name', $product['product_name']);
        $this->db->bind(':description', $product['description']);
        $this->db->bind(':id_category', $product['id_category']);
        $this->db->bind(':weight', $product['weight']);
        $this->db->bind(':dimensions', $product['dimensions']);
        $this->db->bind(':image_url', $product['image_url']);
        $this->db->bind(':discontinued', $product['discontinued']);

        // Execute
        return $this->db->execute();
    }

    // Read a single product by ID
    public function getProduct($id_product) {
        $this->db->query('
            SELECT p.*, v.id_variation, v.variation_name, v.price, d.discount_value, dt.type_name
            FROM products p
            LEFT JOIN variations v ON p.id_product = v.id_product
            LEFT JOIN discount_variations dpv ON v.id_variation = dpv.id_variation
            LEFT JOIN discounts d ON dpv.id_discount = d.id_discount
            LEFT JOIN discount_types dt ON d.id_discount_type = dt.id_discount_type
            WHERE p.id_product = :id_product
        ');
        $this->db->bind(':id_product', $id_product);
        return $this->db->single();
    }

    // Read all products or filter by criteria with pagination
    public function getProducts($criteria = []) {
        $query = '
            SELECT p.*, v.id_variation, v.variation_name, v.price, d.discount_value, dt.type_name
            FROM products p
            LEFT JOIN variations v ON p.id_product = v.id_product
            LEFT JOIN discount_variations dpv ON v.id_variation = dpv.id_variation
            LEFT JOIN discounts d ON dpv.id_discount = d.id_discount
            LEFT JOIN discount_types dt ON d.id_discount_type = dt.id_discount_type
            WHERE 1=1
        ';

        // Build query based on criteria
        if (!empty($criteria['name'])) {
            $query .= ' AND p.product_name LIKE :name';
        }
        if (isset($criteria['category_id'])) {
            $query .= ' AND p.id_category = :category_id';
        }
        if (isset($criteria['discontinued'])) {
            $query .= ' AND p.discontinued = :discontinued';
        }

        // Add pagination if specified
        if (isset($criteria['limit']) && isset($criteria['offset'])) {
            $query .= ' LIMIT :offset, :limit';
        }

        $this->db->query($query);

        // Bind values
        if (!empty($criteria['name'])) {
            $this->db->bind(':name', '%' . $criteria['name'] . '%');
        }
        if (isset($criteria['category_id'])) {
            $this->db->bind(':category_id', $criteria['category_id']);
        }
        if (isset($criteria['discontinued'])) {
            $this->db->bind(':discontinued', $criteria['discontinued']);
        }
        if (isset($criteria['limit']) && isset($criteria['offset'])) {
            $this->db->bind(':limit', $criteria['limit'], PDO::PARAM_INT);
            $this->db->bind(':offset', $criteria['offset'], PDO::PARAM_INT);
        }

        return $this->db->resultSet();
    }

    // Update product details
    public function updateProduct($id_product, $product) {
        $this->db->query('UPDATE products SET product_name = :product_name, description = :description, id_category = :id_category, weight = :weight, dimensions = :dimensions, image_url = :image_url, discontinued = :discontinued WHERE id_product = :id_product');
        
        // Bind values
        $this->db->bind(':product_name', $product['product_name']);
        $this->db->bind(':description', $product['description']);
        $this->db->bind(':id_category', $product['id_category']);
        $this->db->bind(':weight', $product['weight']);
        $this->db->bind(':dimensions', $product['dimensions']);
        $this->db->bind(':image_url', $product['image_url']);
        $this->db->bind(':discontinued', $product['discontinued']);
        $this->db->bind(':id_product', $id_product);

        // Execute
        return $this->db->execute();
    }

    // Delete a product
    public function deleteProduct($id_product) {
        $this->db->query('DELETE FROM products WHERE id_product = :id_product');
        $this->db->bind(':id_product', $id_product);
        return $this->db->execute();
    }
}
