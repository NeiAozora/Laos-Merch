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

}

