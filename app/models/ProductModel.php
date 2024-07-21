<?php

class ProductModel {
    private $db;

    public function __construct() {
        $this->db = new Database();
    }

    // Create a new product
    public function createProduct($product) {
        $this->db->query('
            INSERT INTO products (product_name, description, id_category, weight, dimensions, discontinued)
            VALUES (:product_name, :description, :id_category, :weight, :dimensions, :discontinued)
        ');
        
        // Bind values
        $this->db->bind(':product_name', $product['product_name']);
        $this->db->bind(':description', $product['description']);
        $this->db->bind(':id_category', $product['id_category']);
        $this->db->bind(':weight', $product['weight']);
        $this->db->bind(':dimensions', $product['dimensions']);
        $this->db->bind(':discontinued', $product['discontinued']);

        // Execute
        return $this->db->execute();
    }

    // Read a single product by ID
    public function getProduct($id_product) {
        $this->db->query('
            SELECT p.*, c.category_name, vi.image_url AS product_image, vc.price, vc.stock, v.option_name, t.tag_name
            FROM products p
            LEFT JOIN categories c ON p.id_category = c.id_category
            LEFT JOIN product_images vi ON p.id_product = vi.id_product
            LEFT JOIN variation_combinations vc ON p.id_product = vc.id_product
            LEFT JOIN variation_options v ON vc.id_combination = v.id_combination
            LEFT JOIN product_tags pt ON p.id_product = pt.id_product
            LEFT JOIN tags t ON pt.id_tag = t.id_tag
            WHERE p.id_product = :id_product
        ');
        $this->db->bind(':id_product', $id_product);
        return $this->db->single();
    }

    // Read all products or filter by criteria with pagination
    public function getProducts($criteria = []) {
        $query = '
            SELECT p.*, c.category_name, vi.image_url AS product_image, vc.price, vc.stock, v.option_name, t.tag_name
            FROM products p
            LEFT JOIN categories c ON p.id_category = c.id_category
            LEFT JOIN product_images vi ON p.id_product = vi.id_product
            LEFT JOIN variation_combinations vc ON p.id_product = vc.id_product
            LEFT JOIN variation_options v ON vc.id_combination = v.id_combination
            LEFT JOIN product_tags pt ON p.id_product = pt.id_product
            LEFT JOIN tags t ON pt.id_tag = t.id_tag
            WHERE 1=1
        ';

        // Build query based on criteria
        if (!empty($criteria['name'])) {
            $query .= ' AND p.product_name LIKE :name';
        }
        if (isset($criteria['category_name'])) {
            $query .= ' AND c.category_name = :category_name';
        }
        if (isset($criteria['tag'])) {
            $query .= ' AND t.tag_name = :tag_name';
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
        if (isset($criteria['category_name'])) {
            $this->db->bind(':category_name', $criteria['category_name']);
        }
        if (isset($criteria['tag'])) {
            $this->db->bind(':tag_name', $criteria['tag']);
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
        $this->db->query('
            UPDATE products
            SET product_name = :product_name, description = :description, id_category = :id_category, 
                weight = :weight, dimensions = :dimensions, discontinued = :discontinued
            WHERE id_product = :id_product
        ');
        
        // Bind values
        $this->db->bind(':product_name', $product['product_name']);
        $this->db->bind(':description', $product['description']);
        $this->db->bind(':id_category', $product['id_category']);
        $this->db->bind(':weight', $product['weight']);
        $this->db->bind(':dimensions', $product['dimensions']);
        $this->db->bind(':discontinued', $product['discontinued']);
        $this->db->bind(':id_product', $id_product);

        // Execute
        return $this->db->execute();
    }

    // Delete a product
    public function deleteProduct($id_product) {
        $this->db->query('
            DELETE FROM products WHERE id_product = :id_product
        ');
        $this->db->bind(':id_product', $id_product);
        return $this->db->execute();
    }
}
