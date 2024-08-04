<?php

class OrderModel extends Model {
    protected $db;
    protected $table = 'orders';
    protected $primaryKey = 'id_order';

    public function __construct() {
        $this->db = new Database();
    }
    

    public function getAllOrders($id_user, $status = null){
        $query ="
            SELECT o.id_order, oi.id_order_item, p.product_name, pi.image_url, oi.quantity, GROUP_CONCAT(vo.option_name SEPARATOR ',') as option_names, o.total_price, os.status_name
            FROM orders o
            JOIN order_items oi ON o.id_order = oi.id_order
            JOIN order_statuses os ON o.id_status = os.id_status
            JOIN variation_combinations vc ON oi.id_combination = vc.id_combination
            JOIN combination_details cd ON vc.id_combination = cd.id_combination
            JOIN variation_options vo ON cd.id_option =  vo.id_option
            JOIN products p ON vc.id_product = p.id_product
            JOIN product_images pi ON p.id_product = pi.id_product
            WHERE o.id_user = :id_user
        ";
        if($status !== null AND $status !== 'Semua'){
            $query .= 'AND os.status_name = :status';
        }

        $this->db->query($query);
        $this->db->bind(':id_user', $id_user);
        if($status !== null AND $status !== 'Semua'){
            $this->db->bind(':status', $status);
        }

        $this->db->execute();
        $orders = $this->db->resultSet(PDO::FETCH_ASSOC);
         if (!$orders) {
            $orders = [];
        }

        return $orders;
    }


    public function getOrderById($id_order){
        $this->db->query("
            SELECT o.id_order, o.order_date, os.status_name, o.total_price, s.shipping_method, GROUP_CONCAT(sa.street_address, sa.city, sa.state, sa.postal_code SEPARATOR ',') as address,
                   sa.recipient_name, u.wa_number, p.product_name, pi.image_url, 
                   oi.quantity, GROUP_CONCAT(vo.option_name SEPARATOR ',') as option_names
            FROM orders o
            JOIN order_statuses os ON o.id_status = os.id_status
            JOIN shipments s ON o.id_order = s.id_order
            JOIN users u ON o.id_user = u.id_user
            JOIN shipping_addresses sa ON u.id_user = sa.id_user
            JOIN order_items oi ON o.id_order = oi.id_order
            JOIN variation_combinations vc ON oi.id_combination = vc.id_combination
            JOIN products p ON vc.id_product = p.id_product
            JOIN product_images pi ON p.id_product = pi.id_product
            JOIN combination_details cd ON vc.id_combination = cd.id_combination
            JOIN variation_options vo ON cd.id_option = vo.id_option
            WHERE o.id_order = :id_order
            GROUP BY oi.id_order_item
        ");
        $this->db->bind(':id_order', $id_order);

        $this->db->execute();
        return $this->db->single(PDO::FETCH_ASSOC);
    }
}