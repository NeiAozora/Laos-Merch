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

    public function addShipAddress($id_user,$label_name,$street_address,$city,$state,$postal_code,$extra_note,$is_prioritize,$is_temporary){
        $this->db->query('
            INSERT INTO shipping_addresses (id_user,label_name,street_address,city,state,postal_code,extra_note) 
            VALUES (:id_shipping_address,:id_user,:label_name,:street_address,:city,:state,:postal_code,:extra_note)
        ');

        $this->db->bind(':id_user', $id_user, PDO::PARAM_INT);
        $this->db->bind(':label_name', $label_name);
        $this->db->bind(':street_address', $street_address);
        $this->db->bind(':city', $city);
        $this->db->bind('state', $state);
        $this->db->bind(':postal_code', $postal_code, PDO::PARAM_INT);
        $this->db->bind(':extra_note', $extra_note);
        
        $this->db->execute();
    }

    public function updateShipAddress($id_user, $id_shipping_address, $label_name, $street_address, $city, $state, $postal_code, $extra_note){
        $this->db->query('
            UPDATE shipping_addresses SET label_name = :label_name, street_address = :street_address, city = :city, state = :state, postal_code = :postal_code, extra_note = :extra_note 
            WHERE id_user = :id_user AND id_shipping_address = :id_shipping_address
        ');

        $this->db->bind(':id_user', $id_user, PDO::PARAM_INT);
        $this->db->bind(':id_shipping_address', $id_shipping_address, PDO::PARAM_INT);
        $this->db->bind(':label_name', $label_name);
        $this->db->bind(':street_address', $street_address);
        $this->db->bind(':city', $city);
        $this->db->bind(':state', $state);
        $this->db->bind(':postal_code', $postal_code, PDO::PARAM_INT);
        $this->db->bind(':extra_note', $extra_note);
        
        return $this->db->execute();
    }
}

