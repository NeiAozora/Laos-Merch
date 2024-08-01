<?php

class OrderModel extends Model {
    protected $db;
    protected $table = 'orders';
    protected $primaryKey = 'id_order';

    public function __construct() {
        $this->db = new Database();
    }
    

    public function getAllOrders($id_user, $status = null){
        $query ='
            SELECT oi.id_order_item, p.product_name, oi.quantity, o.total_price, os.status_name, pi.image_url
            FROM order_items oi
            JOIN orders o ON oi.id_order = o.id_order
            JOIN order_statuses os ON o.id_status = os.id_status
            JOIN variation_combinations vc ON oi.id_variation_combination = vc.id_combination
            JOIN products p ON vc.id_product = p.id_product
            JOIN product_images pi ON p.id_product = pi.id_product
            JOIN variation_types vt ON p.id_product = vt.id_product
            WHERE o.id_user = :id_user
        ';
        if($status !== null AND $status !== 'Semua'){
            $query .= 'AND os.status_name = :status';
        }

        $this->db->query($query);
        $this->db->bind(':id_user', $id_user);
        if($status !== null AND $status !== 'Semua'){
            $this->db->bind(':status', $status);
        }

        $this->db->execute();
        return $this->db->resultSet(PDO::FETCH_ASSOC);

    }
}