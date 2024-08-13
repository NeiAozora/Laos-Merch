<?php

class CartItemModel extends Model{
    protected $db;
    protected $table = "cart_items";
    protected $primaryKey = "id_cart_item";
    use StaticInstantiator;

    public function __construct()
    {
        $this->db = new Database();
    }

    // Add cart item
    public function addCartItem($id_user, $id_combination, $quantity)
    {
        $this->db->query('
            INSERT INTO cart_items (id_user, id_combination, quantity) 
            VALUES (:id_user, :id_combination, :quantity) 
        ');
        $this->db->bind(':id_user', $id_user, PDO::PARAM_INT);
        $this->db->bind(':id_combination', $id_combination, PDO::PARAM_INT);
        $this->db->bind(':quantity', $quantity, PDO::PARAM_INT);
        
        return $this->db->execute();
    }

    // Get cart item by id
    public function getCartItemById($id_cart_item)
    {
        $this->db->query('SELECT * FROM cart_items WHERE id_cart_item = :id_cart_item');
        $this->db->bind(':id_cart_item', $id_cart_item, PDO::PARAM_INT);

        return $this->db->single();
    }

    // Get all cart items by user id
    public function getCartItemsByUserId($id_user)
    {
        $this->db->query("SELECT 
        ci.id_cart_item,
        ci.id_user,
        ci.id_combination,
        ci.quantity,
        pi.image_url,
        GROUP_CONCAT(DISTINCT vo.option_name ORDER BY vo.option_name SEPARATOR ', ') AS option_name,
        GROUP_CONCAT(DISTINCT vt.name ORDER BY vt.name SEPARATOR ', ') AS variation_types,
        COALESCE(MAX(d.discount_value), 0) AS discount_value,
        p.product_name,
        p.description,
        p.weight,
        p.dimensions,
        vc.price
    FROM cart_items ci
    JOIN variation_combinations vc ON ci.id_combination = vc.id_combination
    JOIN combination_details cd ON cd.id_combination = vc.id_combination
    JOIN variation_options vo ON vo.id_option = cd.id_option
    JOIN variation_types vt ON vt.id_variation_type = vo.id_variation_type
    JOIN products p ON vc.id_product = p.id_product
    LEFT JOIN discount_products dp ON p.id_product = dp.id_product
    LEFT JOIN discounts d ON dp.id_discount = d.id_discount AND d.end_date > NOW() -- Ensure discount is not expired
    LEFT JOIN product_images pi ON pi.id_product = p.id_product
    WHERE ci.id_user = :id_user
    GROUP BY ci.id_cart_item, ci.id_user, ci.id_combination, ci.quantity, pi.image_url, p.product_name, p.description, p.weight, p.dimensions, vc.price;
    ");
        $this->db->bind(':id_user', $id_user, PDO::PARAM_INT);
        
        return $this->db->resultSet();
    }

    // Get product variation combination
    public function getCombination($id_combination)
    {
        $query ="
            SELECT vc.id_combination, vc.id_product, vc.price, vc.stock, p.product_name, p.description 
            FROM variation_combinations vc 
            JOIN products p ON vc.id_product = p.id_product 
            WHERE vc.id_combination = :id_combination
        ";

        $this->db->query($query);
        $this->db->bind('id_combination', $id_combination);
        return $this->db->single();
    }

    // Update cart item
    public function updateCartItem($id_cart_item, $quantity)
    {
        $this->db->query('
            UPDATE cart_items SET quantity = :quantity, last_updated = NOW() WHERE id_cart_item = :id_cart_item
        ');
        $this->db->bind(':id_cart_item', $id_cart_item, PDO::PARAM_INT);
        $this->db->bind(':quantity', $quantity, PDO::PARAM_INT);
        
        return $this->db->execute();
    }

        // Check if a cart item exists for the given user and combination
    public function getCartItemByUserAndCombination($id_user, $id_combination)
    {
        $this->db->query('
            SELECT * FROM cart_items 
            WHERE id_user = :id_user 
            AND id_combination = :id_combination
        ');
        $this->db->bind(':id_user', $id_user, PDO::PARAM_INT);
        $this->db->bind(':id_combination', $id_combination, PDO::PARAM_INT);

        return $this->db->single();
    }


    // Remove cart item
    public function removeCartItem($id_cart_item)
    {
        $this->db->query('
            DELETE FROM cart_items WHERE id_cart_item = :id_cart_item
        ');
        $this->db->bind(':id_cart_item', $id_cart_item, PDO::PARAM_INT);
        
        return $this->db->execute();
    }
}
