<?php


class UserModel
{
    private $db;

    public function __construct()
    {
        $this->db = new Database();
    }

    // Create a new user
    public function createUser($firebase_uid, $user_name, $email, $wa_number, $id_role)
    {
        $this->db->query('INSERT INTO users (firebase_uid, user_name, email, wa_number, id_role) VALUES (:firebase_uid, :user_name, :email, :wa_number, :id_role)');
        $this->db->bind(':firebase_uid', $firebase_uid);
        $this->db->bind(':user_name', $user_name);
        $this->db->bind(':email', $email);
        $this->db->bind(':wa_number', $wa_number);
        $this->db->bind(':id_role', $id_role);

        return $this->db->execute();
    }

    // Get a single user by id
    public function getUserById($id_user)
    {
        $this->db->query('SELECT * FROM users WHERE id_user = :id_user');
        $this->db->bind(':id_user', $id_user);

        return $this->db->single();
    }

    // Get a single user by id
    public function getUserByFireBaseId($id_user)
    {
        $this->db->query('SELECT * FROM users WHERE id_user = :id_user');
        $this->db->bind(':id_user', $id_user);

        return $this->db->single();
    }


    // Get all users
    public function getAllUsers()
    {
        $this->db->query('SELECT * FROM users');
        return $this->db->resultSet();
    }

    // Update a user
    public function updateUser($id_user, $firebase_uid, $user_name, $email, $wa_number, $id_role, $is_active)
    {
        $this->db->query('UPDATE users SET firebase_uid = :firebase_uid, user_name = :user_name, email = :email, wa_number = :wa_number, id_role = :id_role, is_active = :is_active WHERE id_user = :id_user');
        $this->db->bind(':id_user', $id_user);
        $this->db->bind(':firebase_uid', $firebase_uid);
        $this->db->bind(':user_name', $user_name);
        $this->db->bind(':email', $email);
        $this->db->bind(':wa_number', $wa_number);
        $this->db->bind(':id_role', $id_role);
        $this->db->bind(':is_active', $is_active);

        return $this->db->execute();
    }

    // Delete a user
    public function deleteUser($id_user)
    {
        $this->db->query('DELETE FROM users WHERE id_user = :id_user');
        $this->db->bind(':id_user', $id_user);

        return $this->db->execute();
    }
}
