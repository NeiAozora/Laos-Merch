<?php

class OrderItemModel extends Model {
    protected $table = 'order_items';
    protected $primaryKey = 'id_order_item';
    use StaticInstantiator;

    public function insertItems($orderItems){
        try {
            // Insert into the order_items table
            $orderItemQuery = "
            INSERT INTO order_items (id_order, id_combination, quantity, price, discount_value)
            VALUES (:id_order, :id_combination, :quantity, :price, :discount_value)
        ";
        $this->db->query($orderItemQuery);

        foreach ($orderItems as $item) {
            $this->db->bind(':id_order', $item['id_order']);
            $this->db->bind(':id_combination', $item['id_combination']);
            $this->db->bind(':quantity', $item['quantity']);
            $this->db->bind(':price', $item['price']);

            // Handle the optional discount_value
            if (isset($item['discount_value'])) {
                $this->db->bind(':discount_value', $item['discount_value']);
            } else {
                $this->db->bind(':discount_value', null);
            }

            if (!$this->db->execute()) {
                throw new Exception('Failed to insert order item.');
            }
        }

        
            
        } catch (\Throwable $th) {
            return false;
        }

        return true;
    }

    public function getOrderItemsByIdOrder($idUser, $idOrder){
        $this->db->query("
            SELECT oi.id_order_item,
            o.id_user,
            oi.id_combination,
            oi.quantity,
            pi.image_url,
            GROUP_CONCAT(DISTINCT vo.option_name ORDER BY vo.option_name SEPARATOR ', ') AS option_names,
            GROUP_CONCAT(DISTINCT vt.name ORDER BY vt.name SEPARATOR ', ') AS variation_types,
            p.product_name,
            p.description,
            p.weight,
            p.dimensions,
            oi.price,
            oi.discount_value
        FROM order_items oi
        JOIN orders o ON o.id_order = oi.id_order
        JOIN variation_combinations vc ON oi.id_combination = vc.id_combination
        JOIN combination_details cd ON cd.id_combination = vc.id_combination
        JOIN variation_options vo ON vo.id_option = cd.id_option
        JOIN variation_types vt ON vt.id_variation_type = vo.id_variation_type
        JOIN products p ON vc.id_product = p.id_product
        LEFT JOIN product_images pi ON pi.id_product = p.id_product
        WHERE o.id_order = :id_order AND o.id_user = :id_user
        GROUP BY oi.id_order_item, o.id_user, oi.id_combination, oi.quantity, pi.image_url, p.product_name, p.description, p.weight, p.dimensions, vc.price;
        
        ");

        $this->db->bind(':id_user', $idUser);
        $this->db->bind(':id_order', $idOrder);

        // Execute the query and return the results
        $orders = $this->db->resultSet();
        if (!$orders) {
            $orders = [];
        }
    
        return $orders;
    }

}

