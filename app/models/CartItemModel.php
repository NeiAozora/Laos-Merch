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

    // tambah cart item
    public function createCartItem($id_user, $id_combination, $quantity)
    {
        $this->db->query('
            INSERT INTO cart_items (id_user, id_combination, quantity, date_added, last_updated) 
            VALUES (:id_user, :id_combination, :quantity, NOW(), NOW())
        ');
        $this->db->bind(':id_user', $id_user, PDO::PARAM_INT);
        $this->db->bind(':id_combination', $id_combination, PDO::PARAM_INT);
        $this->db->bind(':quantity', $quantity, PDO::PARAM_INT);
        
        return $this->db->execute();
    }

    // get single cart by id
    public function getCartItemById($id_cart_item)
    {
        $this->db->query('SELECT * FROM cart_items WHERE id_cart_item = :id_cart_item');
        $this->db->bind(':id_cart_item', $id_cart_item, PDO::PARAM_INT);

        return $this->db->single();
    }

    // Get all cart items for a specific user
    public function getCartItemsByUserId($id_user)
    {
        $this->db->query('
            SELECT ci.*, p.product_name, p.description, p.weight, p.dimensions, vc.price
            FROM cart_items ci
            JOIN variation_combination vc ON ci.id_combination = vc.id_combination
            JOIN products p ON vc.id_product = p.id_product
            WHERE ci.id_user = :id_user
        ');
        $this->db->bind(':id_user', $id_user, PDO::PARAM_INT);
        
        return $this->db->resultSet();
    }

    // Get total cost for a user's cart
    public function getTotalCostByUserId($id_user)
    {
        $this->db->query('
            SELECT SUM(vc.price * ci.quantity) as total
            FROM cart_items ci
            JOIN variation_combination vc ON ci.id_combination = vc.id_combination
            WHERE ci.id_user = :id_user
        ');
        $this->db->bind(':id_user', $id_user, PDO::PARAM_INT);
        
        return $this->db->single()['total'];
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

    // hapus cart item
    public function removeCartItem($id_cart_item)
    {
        $this->db->query('
            DELETE FROM cart_items WHERE id_cart_item = :id_cart_item
        ');
        $this->db->bind(':id_cart_item', $id_cart_item, PDO::PARAM_INT);
        
        return $this->db->execute();
    }
}
