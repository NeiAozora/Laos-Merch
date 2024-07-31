<?php

class TempVerificationModel extends Model
{
    protected $db;

    public function __construct()
    {
        $this->db = new Database();
    }

    // Create a new verification record
    public function create($userId, $verificationCode, $tempToken)
    {
        $query = "INSERT INTO temp_verifications (id_user, code, temp_token, created_at, expires_at) 
                VALUES (:id_user, :code, :temp_token, NOW(), DATE_ADD(NOW(), INTERVAL 3 HOUR))";
        $this->db->query($query);
        $this->db->bind(':id_user', $userId);
        $this->db->bind(':code', $verificationCode);
        $this->db->bind(':temp_token', $tempToken);
        $this->db->execute();
    }

    // Get the latest verification record by token, which has not expired
    public function getByToken($tempToken)
    {
        $query = "SELECT * FROM temp_verifications 
                  WHERE temp_token = :temp_token AND expires_at > NOW() 
                  ORDER BY created_at DESC LIMIT 1";
        $this->db->query($query);
        $this->db->bind(':temp_token', $tempToken);
        return $this->db->single();
    }

    // Get the latest verification record by user ID, which has not expired
    public function getByUser($userId)
    {
        $query = "SELECT * FROM temp_verifications 
                  WHERE id_user = :id_user AND expires_at > NOW() 
                  ORDER BY created_at DESC LIMIT 1";
        $this->db->query($query);
        $this->db->bind(':id_user', $userId);
        return $this->db->single();
    }

    // Delete verification records by token
    public function deleteByToken($tempToken)
    {
        $query = "DELETE FROM temp_verifications WHERE temp_token = :temp_token";
        $this->db->query($query);
        $this->db->bind(':temp_token', $tempToken);
        $this->db->execute();
    }

    // Delete verification records by user ID
    public function deleteByUser($userId)
    {
        $query = "DELETE FROM temp_verifications WHERE id_user = :id_user";
        $this->db->query($query);
        $this->db->bind(':id_user', $userId);
        $this->db->execute();
    }
}
