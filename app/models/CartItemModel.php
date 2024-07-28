<?php

class CartItemModel extends Model {
    protected $db;
    protected $table = 'cart_items';
    protected $primaryKey = 'id_cart_item';

    public function __construct() {
        $this->db = new Database();
    }

    //add cart item
    public function addCartItem($cart){
        $date = date('Y-m-d H:i:s');
        $this->db->query('
            INSERT INTO cart_items (id_cart_item, id_user, id_combination, quantity, date_added, last_updated)
            VALUES (:id_cart_item, :id_user, :id_combination, :quantity, :date_added, :last_updated)
        ');
        
        // Bind values
        $this->db->bind(':id_cart_item', $cart['id_cart_item']);
        $this->db->bind(':id_user', $cart['id_user']);
        $this->db->bind(':id_combination', $cart['id_combination']);
        $this->db->bind(':quantity', $cart['quantity']);
        $this->db->bind(':date_added', $cart[$date]);
        $this->db->bind(':last_updated', $cart[$date]);

        // Execute
        return $this->db->execute();
    }

    //read cart item
    public function getCartItem($id_cart_item){
        $this->db->query('
            SELECT c.id_cart_item, u.first_name || || u.last_name, vc.price, c.quantity, c.date_added, c.last_updated
            from cart_items c
            JOIN users u on c.id_user = u.id_user
            JOIN variation_combinations vc on c.id_combination = vc.id_combination
            GROUP BY c.id_cart_item
            WHERE c.id_cart_item = :id_cart_item
        ');
        $this->db->bind(':id_cart_item', $id_cart_item, PDO::PARAM_INT);
        return $this->db->single();
    }

    //update cart item
    public function updateCartItem($cart, $id_cart_item){
        $date = date('Y-m-d H:i:s');
        $this->db->query('
            UPDATE cart_items SET id_user = :id_user, id_combination = :id_combination, quantity = :quantity, date_added = :date_added, last_updated = :last_updated
            WHERE id_cart_item = :id_cart_item
        ');
        
        // Bind values
        $this->db->bind(':id_user', $cart['id_user']);
        $this->db->bind(':id_combination', $cart['id_combination']);
        $this->db->bind(':quantity', $cart['quantity']);
        $this->db->bind(':date_added', $cart[$date]);
        $this->db->bind(':last_updated', $cart[$date]);
        $this->db->bind(':id_cart_item', $id_cart_item);

        // Execute
        return $this->db->execute();
    }

    //delete cart item
    public function removeCartItem($id_cart_item) {
        $this->db->query('
            DELETE FROM cart_items WHERE id_cart_items = :id_cart_items
        ');
        $this->db->bind(':id_cart_item', $id_cart_item);
        return $this->db->execute();
    }
}

