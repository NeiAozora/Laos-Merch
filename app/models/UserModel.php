<?php


class UserModel extends Model
{
    protected $db;
    protected $table = "users";
    protected $primaryKey = "id_user";
    use StaticInstantiator;



    public function __construct()
    {
        $this->db = new Database();
    }

    // Create a new user
    public function createUser($id_firebase, $user_name, $email, $wa_number, $profile_picture )
    {
        $this->db->query('INSERT INTO users (id_firebase, user_name, email, wa_number, id_role, profile_picture ) VALUES (:id_firebase, :user_name, :email, :wa_number, :id_role, :profile_picture )');
        $this->db->bind(':id_firebase', $id_firebase);
        $this->db->bind(':user_name', $user_name);
        $this->db->bind(':email', $email);
        $this->db->bind(':wa_number', $wa_number);
        $this->db->bind(':profile_picture ', $profile_picture );
        $this->db->bind(':profile_picture ', $profile_picture );


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
    public function getUserByFireBaseId($id_firebase)
    {
        $this->db->query('SELECT * FROM users WHERE id_firebase = :id_firebase');
        $this->db->bind(':id_firebase', $id_firebase);

        return $this->db->single();
    }


    // Get all users
    public function getAllUsers()
    {
        $this->db->query('SELECT * FROM users');
        return $this->db->resultSet();
    }

    // Update a user
    public function updateUser($id_user, $id_firebase, $user_name, $email, $wa_number, $id_role, $is_active)
    {
        $this->db->query('UPDATE users SET id_firebase = :id_firebase, user_name = :user_name, email = :email, wa_number = :wa_number, id_role = :id_role, is_active = :is_active WHERE id_user = :id_user');
        $this->db->bind(':id_user', $id_user);
        $this->db->bind(':id_firebase', $id_firebase);
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
