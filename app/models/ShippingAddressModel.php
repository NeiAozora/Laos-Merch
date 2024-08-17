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

    public function updateShipAddress($id_user, $id_shipping_address, $label_name, $street_address, $city, $state, $postal_code, $extra_note, $is_prioritize, $is_temporary){
        $this->db->query('
            UPDATE shipping_addresses 
            SET 
                id_shipping_address = :id_shipping_address, 
                label_name = :label_name, 
                street_address = :street_address, 
                city = :city, 
                state = :state, 
                postal_code = :postal_code, 
                extra_note = :extra_note, 
                is_prioritize = :is_prioritize, 
                is_temporary = :is_temporary
            WHERE 
                id_user = :id_user AND id_shipping_address = :id_shipping_address
        ');

        $this->db->bind(':id_shipping_address', $id_shipping_address);
        $this->db->bind(':label_name', $label_name);
        $this->db->bind(':street_address', $street_address);
        $this->db->bind(':city', $city);
        $this->db->bind(':state', $state);
        $this->db->bind(':postal_code', $postal_code);
        $this->db->bind(':extra_note', $extra_note);
        $this->db->bind(':is_prioritize', $is_prioritize);
        $this->db->bind(':is_temporary', $is_temporary);
        $this->db->bind(':id_user', $id_user);

        return $this->db->execute();
    }

    public function updateShippingAddressPriority($id_user, $id_shipping_address, $prioritize) {
        // Get all addresses for the user
        $addresses = $this->getShipAddressByUser($id_user);

        if (count($addresses) < 1) {
            throw new Exception('No shipping addresses found for the user.');
        }

        // Check if the target address exists
        $targetAddress = null;
        foreach ($addresses as $address) {
            if ($address['id_shipping_address'] == $id_shipping_address) {
                $targetAddress = $address;
                break;
            }
        }

        if ($targetAddress === null) {
            throw new Exception('Shipping address not found.');
        }

        // Reset the priority for all addresses of the user
        $resetDb = new Database();
        $resetDb->query('
            UPDATE shipping_addresses 
            SET is_prioritize = FALSE 
            WHERE id_user = :id_user
        ');
        $resetDb->bind(':id_user', $id_user);
        $resetDb->execute();

        // Set is_prioritize to true for the target address
        $updateDb = new Database();
        $updateDb->query('
            UPDATE shipping_addresses 
            SET is_prioritize = :prioritize 
            WHERE id_user = :id_user AND id_shipping_address = :id_shipping_address
        ');
        $updateDb->bind(':prioritize', $prioritize);
        $updateDb->bind(':id_user', $id_user);
        $updateDb->bind(':id_shipping_address', $id_shipping_address);

        return $updateDb->execute();
    }
}
