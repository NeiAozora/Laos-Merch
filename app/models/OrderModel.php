<?php

class OrderModel extends Model {
    protected $db;
    protected $table = 'orders';
    protected $primaryKey = 'id_order';

    public function __construct() {
        $this->db = new Database();
    }
    

    public function getAllOrders($id_user){
        $this->db->query('
            SELECT o.id_order, o.id_user, o.order_date, o.total_price, s.status_name
            FROM orders o 
            JOIN order_statuses s on o.id_status = s.id_status
            WHERE o.id_user = :id_user
        ');
        $this->db->bind(':id_user', $id_user);
        $this->db->execute();
        return $this->db->resultSet(PDO::FETCH_ASSOC);

    }

    public function getOrderById($id_order, $id_user){
        $this->db->query('
            SELECT o.id_order, o.id_user, o.order_date, o.total_price, s.status_name
            FROM orders o 
            JOIN order_statuses s on o.id_status = s.id_status
            WHERE o.id_order = :id_order AND o.id_user = :id_user
        ');
        $this->db->bind(':id_order', $id_order);
        $this->db->bind(':id_user', $id_user);
        $this->db->execute();
        $order = $this->db->single(PDO::FETCH_ASSOC);

        $orderItems = "
            SELECT oi.id_order_item, oi.quantity, p.product_name, p.product_image, p.product_color, p.product_size
            FROM order_items oi
            JOIN variation_combinations vc ON oi.id_variation_combination = vc.id_variation_combination
            JOIN products p ON vc.id_product = p.id_product
            WHERE oi.id_order = :id_order
        ";
        $this->db->query($orderItems);
        $this->db->bind(':id_order', $id_order);
        $this->db->execute();
        $order['items'] = $this->db->ResultSet(PDO::FETCH_ASSOC);

        return $order;
    }
    
}

