<?php

class ProductModel extends Model {
    protected $db;
    protected $table = "products";
    protected $primaryKey = "id_product";
    use StaticInstantiator;

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
            SELECT 
                p.id_product, 
                p.product_name, 
                p.description, 
                p.weight, 
                p.dimensions, 
                p.date_added, 
                p.last_updated, 
                p.discontinued,
                c.category_name,
                GROUP_CONCAT(DISTINCT t.tag_name ORDER BY t.tag_name SEPARATOR ", ") AS tags,
                MIN(vc.price) AS price,
                COALESCE(MAX(d.discount_value), 0) AS discount_value, -- Maximum discount value, default 0 if none
                MIN(pi.image_url) AS product_image
            FROM products p
            LEFT JOIN categories c ON p.id_category = c.id_category
            LEFT JOIN product_images pi ON p.id_product = pi.id_product
            LEFT JOIN variation_combinations vc ON p.id_product = vc.id_product
            LEFT JOIN product_tags pt ON p.id_product = pt.id_product
            LEFT JOIN tags t ON pt.id_tag = t.id_tag
            LEFT JOIN discount_products dp ON p.id_product = dp.id_product
            LEFT JOIN discounts d ON dp.id_discount = d.id_discount
            WHERE p.id_product = :id_product
            GROUP BY p.id_product
        ');
        $this->db->bind(':id_product', $id_product, PDO::PARAM_INT);
        return $this->db->single();
    }

    public function getProducts($criteria = [], $strictAndOperator = false) {
        $query = '
            SELECT 
                p.id_product, 
                p.product_name, 
                p.description, 
                p.weight, 
                p.dimensions, 
                p.date_added, 
                p.last_updated, 
                p.discontinued,
                c.category_name,
                GROUP_CONCAT(DISTINCT t.tag_name ORDER BY t.tag_name SEPARATOR ", ") AS tags,
                MIN(vc.price) AS avg_price,
                COALESCE(MAX(d.discount_value), 0) AS discount_value, -- Maximum discount value, default 0 if none
                (SELECT pi.image_url
                 FROM product_images pi
                 WHERE pi.id_product = p.id_product
                 ORDER BY pi.id_product_image
                 LIMIT 1) AS product_image,
                COALESCE(AVG(r.rating), 0) AS avg_rating
            FROM products p
            LEFT JOIN categories c ON p.id_category = c.id_category
            LEFT JOIN variation_combinations vc ON p.id_product = vc.id_product
            LEFT JOIN product_tags pt ON p.id_product = pt.id_product
            LEFT JOIN tags t ON pt.id_tag = t.id_tag
            LEFT JOIN (
                SELECT dp.id_product, d.discount_value
                FROM discount_products dp
                JOIN discounts d ON dp.id_discount = d.id_discount
                WHERE d.end_date > NOW() -- Ensure discount is not expired
                ORDER BY d.start_date DESC
                LIMIT 1
            ) AS d ON p.id_product = d.id_product
            LEFT JOIN reviews r ON vc.id_combination = r.id_combination
        ';
    
        // Add WHERE conditions
        $conditions = [];
        if (!$strictAndOperator) {
            if (!empty($criteria['name'])) {
                $conditions[] = 'p.product_name LIKE :name';
            }
            if (isset($criteria['category_name'])) {
                $conditions[] = 'c.category_name = :category_name';
            }
            if (isset($criteria['tag'])) {
                $conditions[] = 'LOWER(t.tag_name) LIKE LOWER(:tag_name)';
            }
        } else {
            if (!empty($criteria['name'])) {
                $conditions[] = 'p.product_name LIKE :name';
            }
            if (isset($criteria['category_name'])) {
                $conditions[] = 'c.category_name = :category_name';
            }
            if (isset($criteria['tag'])) {
                $conditions[] = 'LOWER(t.tag_name) LIKE LOWER(:tag_name)';
            }
        }
        if (isset($criteria['discontinued'])) {
            $conditions[] = 'p.discontinued = :discontinued';
        }
    
        if (!empty($conditions)) {
            $query .= ' WHERE ' . ($strictAndOperator ? implode(' AND ', $conditions) : implode(' OR ', $conditions));
        }
    
        // Add GROUP BY and pagination if specified
        $query .= ' GROUP BY p.id_product';
    
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
            $this->db->bind(':tag_name', '%' . $criteria['tag'] . '%');
        }
        if (isset($criteria['discontinued'])) {
            $this->db->bind(':discontinued', $criteria['discontinued'], PDO::PARAM_BOOL);
        }
        if (isset($criteria['limit']) && isset($criteria['offset'])) {
            $this->db->bind(':limit', $criteria['limit'], PDO::PARAM_INT);
            $this->db->bind(':offset', $criteria['offset'], PDO::PARAM_INT);
        }
    
        return $this->db->resultSet();
    }

    public function getTotalProducts($criteria = [], $strictAndOperator = false) {
        // Base query
        $query = '
            SELECT COUNT(DISTINCT p.id_product) as total_products
            FROM products p
            LEFT JOIN categories c ON p.id_category = c.id_category
            LEFT JOIN product_tags pt ON p.id_product = pt.id_product
            LEFT JOIN tags t ON pt.id_tag = t.id_tag
            LEFT JOIN discount_products dp ON p.id_product = dp.id_product
            LEFT JOIN discounts d ON dp.id_discount = d.id_discount
            WHERE 1=1
        ';
        
        // Add conditions based on criteria
        $conditions = [];
        if (!empty($criteria['name'])) {
            $conditions[] = 'p.product_name LIKE :name';
        }
        if (isset($criteria['category_name'])) {
            $conditions[] = 'c.category_name = :category_name';
        }
        if (isset($criteria['tag'])) {
            $conditions[] = 'LOWER(t.tag_name) LIKE LOWER(:tag)';
        }
        if (isset($criteria['discontinued'])) {
            $conditions[] = 'p.discontinued = :discontinued';
        }
    
        // Join conditions with AND/OR based on $strictAndOperator
        if (!empty($conditions)) {
            if ($strictAndOperator) {
                $query .= ' AND ' . implode(' AND ', $conditions);
            } else {
                $query .= ' AND (' . implode(' OR ', $conditions) . ')';
            }
        }
    
        // Prepare the query
        $this->db->query($query);
    
        // Bind values
        if (!empty($criteria['name'])) {
            $this->db->bind(':name', '%' . $criteria['name'] . '%');
        }
        if (isset($criteria['category_name'])) {
            $this->db->bind(':category_name', $criteria['category_name']);
        }
        if (isset($criteria['tag'])) {
            $this->db->bind(':tag', '%' . $criteria['tag'] . '%');
        }
        if (isset($criteria['discontinued'])) {
            $this->db->bind(':discontinued', $criteria['discontinued'], PDO::PARAM_BOOL);
        }
    
        // Execute the query and return result
        return $this->db->single()['total_products'];
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
