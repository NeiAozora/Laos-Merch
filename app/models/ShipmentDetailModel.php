<?php


class ShipmentDetailModel extends Model {
    protected $table = 'shipment_details';
    protected $primaryKey = 'id_detail';
    protected $db;
    use StaticInstantiator;


    public function __construct()
    {
        $this->db = new Database;
    }
    /**
     * Get all details and statuses for a specific shipment.
     *
     * @param int $id_shipment The ID of the shipment to fetch details for.
     * @return array The result set containing all status updates for the shipment.
     */
    public function getShipmentDetailsWithStatus($id_shipment) {
        $this->db->query("
            SELECT 
                sd.id_detail ,
                sd.id_shipment,
                sd.detail_date ,
                sd.detail_description ,
                ss.id_status ,
                ss.status_name
            FROM shipment_details sd
            JOIN shipment_statuses ss ON sd.id_status = ss.id_status
            WHERE sd.id_shipment = :id_shipment
            ORDER BY sd.detail_date DESC
        ");
        $this->db->bind(':id_shipment', $id_shipment);
        return $this->db->resultSet();
    }

    /**
     * Insert a new shipment detail with status.
     *
     * @param int $id_shipment The ID of the shipment.
     * @param int $id_status The ID of the status.
     * @param string $detail_description A description of the status.
     * @return bool Whether the insertion was successful.
     */
    public function insertShipmentDetail($id_shipment, $id_status, $detail_description) {
        $this->db->query("
            INSERT INTO shipment_details (id_shipment, id_status, detail_date, detail_description)
            VALUES (:id_shipment, :id_status, NOW(), :detail_description)
        ");
        $this->db->bind(':id_shipment', $id_shipment);
        $this->db->bind(':id_status', $id_status);
        $this->db->bind(':detail_description', $detail_description);
        return $this->db->execute();
    }

    /**
     * Update an existing shipment detail.
     *
     * @param int $detail_id The ID of the detail to update.
     * @param int $id_status The new status ID.
     * @param string $detail_description The new description for the status.
     * @return bool Whether the update was successful.
     */
    public function updateShipmentDetail($detail_id, $id_status, $detail_description) {
        $this->db->query("
            UPDATE shipment_details
            SET id_status = :id_status, detail_description = :detail_description
            WHERE id_detail = :detail_id
        ");
        $this->db->bind(':id_status', $id_status);
        $this->db->bind(':detail_description', $detail_description);
        $this->db->bind(':detail_id', $detail_id);
        return $this->db->execute();
    }

    /**
     * Delete a shipment detail by ID.
     *
     * @param int $detail_id The ID of the detail to delete.
     * @return bool Whether the deletion was successful.
     */
    public function deleteShipmentDetail($detail_id) {
        $this->db->query("
            DELETE FROM shipment_details
            WHERE id_detail = :detail_id
        ");
        $this->db->bind(':detail_id', $detail_id);
        return $this->db->execute();
    }
}
