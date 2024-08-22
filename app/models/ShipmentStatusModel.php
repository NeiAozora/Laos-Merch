<?php

class ShipmentStatusModel extends Model {
    protected $db;
    protected $table = 'shipment_statuses';
    protected $primaryKey = 'id_status';
    use StaticInstantiator;


    public function __construct() {
        $this->db = new Database();
    }


    public function createStatus($status_name) {
        $this->db->query("INSERT INTO shipment_statuses (status_name) VALUES (:status_name)");
        $this->db->bind(':status_name', $status_name);
        return $this->db->execute();
    }

    public function getStatuses($id = null) {
        if ($id) {
            $this->db->query("SELECT * FROM shipment_statuses WHERE id_status = :id_status");
            $this->db->bind(':id_status', $id);
            return $this->db->single();
        } else {
            $this->db->query("SELECT * FROM shipment_statuses");
            return $this->db->resultSet();
        }
    }

    public function updateStatus($id, $status_name) {
        $this->db->query("UPDATE shipment_statuses SET status_name = :status_name WHERE id_status = :id_status");
        $this->db->bind(':status_name', $status_name);
        $this->db->bind(':id_status', $id);
        return $this->db->execute();
    }

    public function deleteStatus($id) {
        $this->db->query("DELETE FROM shipment_statuses WHERE id_status = :id_status");
        $this->db->bind(':id_status', $id);
        return $this->db->execute();
    }
}
