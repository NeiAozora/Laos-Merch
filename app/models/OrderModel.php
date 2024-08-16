<?php

class OrderModel extends Model {
    protected $db;
    protected $table = 'orders';
    protected $primaryKey = 'id_order';
    use StaticInstantiator;

    public function __construct() {
        $this->db = new Database();
    }
    
    public function getAllOrders($id_user, $status = null){
        $query ="
            SELECT o.id_order, oi.id_order_item, p.product_name, pi.image_url, oi.quantity, GROUP_CONCAT(vo.option_name SEPARATOR ',') as option_names, o.total_price, os.status_name, o.order_date
            FROM orders o
            JOIN order_items oi ON o.id_order = oi.id_order
            JOIN order_statuses os ON o.id_status = os.id_status
            JOIN variation_combinations vc ON oi.id_combination = vc.id_combination
            JOIN combination_details cd ON vc.id_combination = cd.id_combination
            JOIN variation_options vo ON cd.id_option = vo.id_option
            JOIN products p ON vc.id_product = p.id_product
            LEFT JOIN product_images pi ON p.id_product = pi.id_product -- Updated to LEFT JOIN to handle cases where there may be no images
            WHERE o.id_user = :id_user
        ";
        if ($status !== null && $status !== 'Semua') {
            $query .= ' AND os.status_name = :status';
        }

        $query .= " GROUP BY o.id_order, oi.id_order_item, p.product_name, pi.image_url, oi.quantity, o.total_price, os.status_name, o.order_date";

        $this->db->query($query);
        $this->db->bind(':id_user', $id_user);
        if ($status !== null && $status !== 'Semua') {
            $this->db->bind(':status', $status);
        }

        $this->db->execute();
        $orders = $this->db->resultSet(PDO::FETCH_ASSOC);
        if (!$orders) {
            $orders = [];
        }

        return $orders;
    }

    public function getOrderById($id, $id_user){
        $this->db->query("
            SELECT o.id_order, o.order_date, os.status_name, o.total_price, o.shipping_fee, o.service_fee, o.handling_fee, pm.method_name as payment_method,
                   CONCAT_WS(', ', sa.street_address, sa.city, sa.state, sa.postal_code) as address, sa.label_name as recipient_name, u.wa_number, p.product_name, pi.image_url, 
                   oi.quantity, oi.price, GROUP_CONCAT(vo.option_name SEPARATOR ',') as option_names
            FROM orders o
            JOIN order_statuses os ON o.id_status = os.id_status
            LEFT JOIN shipments s ON o.id_order = s.id_order
            JOIN users u ON o.id_user = u.id_user
            LEFT JOIN carriers c ON s.id_carrier = c.id_carrier
            LEFT JOIN shipping_addresses sa ON sa.id_shipping_address = o.id_shipping_address
            JOIN order_items oi ON o.id_order = oi.id_order
            JOIN variation_combinations vc ON oi.id_combination = vc.id_combination
            JOIN products p ON vc.id_product = p.id_product
            LEFT JOIN product_images pi ON p.id_product = pi.id_product -- Updated to LEFT JOIN to handle cases where there may be no images
            JOIN combination_details cd ON vc.id_combination = cd.id_combination
            JOIN variation_options vo ON cd.id_option = vo.id_option
            JOIN payment_methods pm ON o.id_payment_method = pm.id_payment_method
            WHERE o.id_order = :id_order AND o.id_user = :id_user
        ");
        $this->db->bind(':id_order', $id);
        $this->db->bind(':id_user', $id_user);

        $this->db->execute();
        return $this->db->single(PDO::FETCH_ASSOC);
    }

    public function updateOrderStatus($id_order, $status){
        $this->db->query('
            UPDATE orders SET id_status = (SELECT id_status FROM order_statuses WHERE status_name = :status) WHERE id_order = :id_order
        ');

        $this->db->bind(':id_order', $id_order);
        $this->db->bind(':status', $status);

        return $this->db->execute();
    }

    /**
     * Delete orders based on a time interval, status, and increment attempt count.
     *
     * @param int $userId User ID for the orders.
     * @param string $timeInterval Time interval string (e.g., '30m' for 30 minutes, '1h' for 1 hour).
     * @return bool True if successful, false otherwise.
     */
    public function deleteOrdersByInterval($userId, $timeInterval) {
        // Extract the numeric value and the time unit (m for minutes, h for hours)
        $intervalValue = (int) substr($timeInterval, 0, -1);
        $intervalUnit = substr($timeInterval, -1);

        // Determine the interval type based on the time unit
        $intervalType = ($intervalUnit === 'm') ? 'MINUTE' : 'HOUR';

        try {
            // Increment `attempt_count` for rows that meet the condition
            $incrementQuery = "
                UPDATE orders
                SET attempt_count = attempt_count + 1
                WHERE id_user = :user_id
                AND id_status = 1
                AND order_date < NOW() - INTERVAL :interval_value $intervalType
            ";
            $this->db->query($incrementQuery);
            $this->db->bind(':user_id', $userId);
            $this->db->bind(':interval_value', $intervalValue);
            if (!$this->db->execute()) {
                throw new Exception('Failed to increment attempt count.');
            }

            // Delete the rows after incrementing
            $deleteQuery = "
                DELETE FROM orders
                WHERE id_user = :user_id
                AND id_status = 1
                AND order_date < NOW() - INTERVAL :interval_value $intervalType
            ";
            $this->db->query($deleteQuery);
            $this->db->bind(':user_id', $userId);
            $this->db->bind(':interval_value', $intervalValue);
            if (!$this->db->execute()) {
                throw new Exception('Failed to delete orders.');
            }

            return true;
        } catch (Exception $e) {
            // Handle the exception, e.g., log the error or display a message
            // No transaction, just ensure that errors are handled
            return false;
        }
    }

    public function insertOrder($orderData) {
        try {
            // Insert into the orders table
            $orderQuery = "
                INSERT INTO orders (id_user, id_status, total_price, shipping_fee, service_fee, handling_fee, id_payment_method, id_shipping_address)
                VALUES (:id_user, :id_status, :total_price, :shipping_fee, :service_fee, :handling_fee, :id_payment_method, :id_shipping_address)
            ";
            $this->db->query($orderQuery);
            $this->db->bind(':id_user', $orderData['id_user']);
            $this->db->bind(':id_status', $orderData['id_status']);
            $this->db->bind(':total_price', $orderData['total_price']);
            $this->db->bind(':shipping_fee', $orderData['shipping_fee']);
            $this->db->bind(':service_fee', $orderData['service_fee']);
            $this->db->bind(':handling_fee', $orderData['handling_fee']);
            $this->db->bind(':id_payment_method', $orderData['id_payment_method']);
            $this->db->bind(':id_shipping_address', $orderData['id_shipping_address']);
    
            if (!$this->db->execute()) {
                throw new Exception('Failed to insert order.');
            }
    
            // Assuming you have an auto-increment column for `id_order`
            $id_order = $this->db->dbh->lastInsertId();
    
            return $id_order;
        } catch (Exception $e) {
            // Handle any exceptions here, such as logging errors
            return false;
        }
    }
    
}
