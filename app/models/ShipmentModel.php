<?php

class ShipmentModel extends Model {
    protected $table = 'shipments';
    protected $primaryKey = 'id_shipment';
    protected $db;
    use StaticInstantiator;

    public function __construct() {
        $this->db = new Database();
    }


    // Create a new shipment
    public function createShipment($data) {
        $this->db->query("
            INSERT INTO shipments 
            (id_order, id_shipment_company, id_carrier, tracking_number, 
            expected_delivery_date, actual_delivery_date) 
            VALUES 
            (:id_order, :id_shipment_company, :id_carrier, :tracking_number, 
            :expected_delivery_date, :actual_delivery_date)
        ");
        $this->db->bind(':id_order', $data['id_order']);
        $this->db->bind(':id_shipment_company', $data['id_shipment_company']);
        $this->db->bind(':id_carrier', $data['id_carrier']);
        $this->db->bind(':tracking_number', $data['tracking_number']);
        $this->db->bind(':expected_delivery_date', $data['expected_delivery_date']);
        $this->db->bind(':actual_delivery_date', $data['actual_delivery_date']);
        
        $this->db->execute();
    
        // Return the ID of the newly inserted row
        return $this->db->dbh->lastInsertId();
    }
    
    

    // Get all shipments or a specific shipment by ID
    public function getShipment($id = null) {
        if ($id) {
            $this->db->query("
                SELECT 
                    s.id_shipment,
                    s.id_order,
                    s.tracking_number,
                    s.shipment_date,
                    s.expected_delivery_date,
                    s.actual_delivery_date,
                    sc.company_name AS shipment_company_name,
                    sc.company_email AS shipment_company_email,
                    sc.company_website AS shipment_company_website,
                    c.carrier_name,
                    c.wa_number AS carrier_wa_number,
                    c.email AS carrier_email
                FROM shipments s
                LEFT JOIN shipment_companies sc ON s.id_shipment_company = sc.id_shipment_company
                LEFT JOIN carriers c ON s.id_carrier = c.id_carrier
                WHERE s.id_shipment = :id_shipment
            ");
            $this->db->bind(':id_shipment', $id);
            return $this->db->single();
        } else {
            $this->db->query("
                SELECT 
                    s.id_shipment,
                    s.id_order,
                    s.tracking_number,
                    s.shipment_date,
                    s.expected_delivery_date,
                    s.actual_delivery_date,
                    sc.company_name AS shipment_company_name,
                    sc.company_email AS shipment_company_email,
                    sc.company_website AS shipment_company_website,
                    c.carrier_name,
                    c.wa_number AS carrier_wa_number,
                    c.email AS carrier_email
                FROM shipments s
                LEFT JOIN shipment_companies sc ON s.id_shipment_company = sc.id_shipment_company
                LEFT JOIN carriers c ON s.id_carrier = c.id_carrier
            ");
            return $this->db->resultSet();
        }
    }

        // Get all shipments or a specific shipment by ID
        public function getShipmentByIdOrder($id) {
                $this->db->query("
                    SELECT 
                        s.id_shipment,
                        s.id_order,
                        s.tracking_number,
                        s.shipment_date,
                        s.expected_delivery_date,
                        s.actual_delivery_date,
                        sc.company_name AS shipment_company_name,
                        sc.company_email AS shipment_company_email,
                        sc.company_website AS shipment_company_website,
                        c.carrier_name,
                        c.wa_number AS carrier_wa_number,
                        c.email AS carrier_email
                    FROM shipments s
                    LEFT JOIN shipment_companies sc ON s.id_shipment_company = sc.id_shipment_company
                    LEFT JOIN carriers c ON s.id_carrier = c.id_carrier
                    WHERE s.id_order = :id_order
                ");
                $this->db->bind(':id_order', $id);
                return $this->db->single();
        }

    // Update a specific shipment
    public function updateShipment($id, $data) {
        $this->db->query("
            UPDATE shipments 
            SET 
                id_order = :id_order,
                id_shipment_company = :id_shipment_company,
                id_carrier = :id_carrier,
                tracking_number = :tracking_number,
                shipment_date = :shipment_date,
                expected_delivery_date = :expected_delivery_date,
                actual_delivery_date = :actual_delivery_date
            WHERE id_shipment = :id_shipment
        ");
        $this->db->bind(':id_order', $data['id_order']);
        $this->db->bind(':id_shipment_company', $data['id_shipment_company']);
        $this->db->bind(':id_carrier', $data['id_carrier']);
        $this->db->bind(':tracking_number', $data['tracking_number']);
        $this->db->bind(':shipment_date', $data['shipment_date']);
        $this->db->bind(':expected_delivery_date', $data['expected_delivery_date']);
        $this->db->bind(':actual_delivery_date', $data['actual_delivery_date']);
        $this->db->bind(':id_shipment', $id);
        return $this->db->execute();
    }

    // Delete a specific shipment
    public function deleteShipment($id) {
        $this->db->query("DELETE FROM shipments WHERE id_shipment = :id_shipment");
        $this->db->bind(':id_shipment', $id);
        return $this->db->execute();
    }
}
