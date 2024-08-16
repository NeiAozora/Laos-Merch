<?php

class ShippingAddressModel extends Model {
    protected $table = 'shipping_addresses';
    protected $primaryKey = 'id_shipping_address';
    use StaticInstantiator;

    public function __construct()
    {
        $this->db = new Database();
    }

    public function getShipAddressByUser($id_user){
        $this->db->query('SELECT * FROM shipping_addresses WHERE id_user = :id_user');
        $this->db->bind(':id_user', $id_user);
        return $this->db->resultSet();
    }

    public function updateShipAddress($id_user, $id_shipping_addresses, $recipient_name, $street_address, $city, $state, $postal_code, $extra_note){
        $this->db->query('
            UPDATE shipping_addresses SET id_shipping_address = :id_shipping_address, recipient_name = :recipient_name, street_address = :street_address, city = :city, state = :state, postal_code = :postal_code, extra_note = :extra_note 
            WHERE id_user = :id_user
        ');

        $this->db->bind(':id_shipping_addresses', $id_shipping_addresses);
        $this->db->bind(':recipient_name', $recipient_name);
        $this->db->bind(':street_address', $street_address);
        $this->db->bind(':city', $city);
        $this->db->bind(':postal_code', $postal_code);
        $this->db->bind(':extra_note', $extra_note);
        $this->db->bind(':id_user', $id_user);
        
        return $this->db->execute();
    }
}

