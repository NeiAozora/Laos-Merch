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
    public function createUser($id_firebase, $username, $first_name, $last_name, $email, $wa_number, $id_role, $profile_picture)
    {
        $this->db->query('INSERT INTO users (id_firebase, username, first_name, last_name, email, wa_number, id_role, profile_picture) VALUES 
                          (:id_firebase, :username, :first_name, :last_name, :email, :wa_number, :id_role, :profile_picture)');
        $this->db->bind(':id_firebase', $id_firebase, PDO::PARAM_STR);
        $this->db->bind(':username', $username);
        $this->db->bind(':first_name', $first_name);
        $this->db->bind(':last_name', $last_name);
        $this->db->bind(':email', $email);
        $this->db->bind(':wa_number', $wa_number);
        $this->db->bind(':id_role', $id_role, PDO::PARAM_INT);
        $this->db->bind(':profile_picture', $profile_picture);
    
        return $this->db->resultSet();
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
    public function updateUser($id_user, $username, $first_name, $last_name, $email, $wa_number, $id_role, $profile_picture)
    {
        $this->db->query('UPDATE users SET username = :username, first_name = :first_name, last_name = :last_name, email = :email, wa_number = :wa_number, id_role = :id_role, profile_picture = :profile_picture WHERE id_user = :id_user');
        $this->db->bind(':id_user', $id_user, PDO::PARAM_INT);
        $this->db->bind(':username', $username);
        $this->db->bind(':first_name', $first_name);
        $this->db->bind(':last_name', $last_name);
        $this->db->bind(':email', $email);
        $this->db->bind(':wa_number', $wa_number);
        $this->db->bind(':id_role', $id_role, PDO::PARAM_INT); // Assuming id_role is an integer
        $this->db->bind(':profile_picture', $profile_picture);

        return $this->db->resultSet();
    }


    // Delete a user
    public function deleteUser($id_user)
    {
        $this->db->query('DELETE FROM users WHERE id_user = :id_user');
        $this->db->bind(':id_user', $id_user);

        return $this->db->execute();
    }
}
