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
    public function createUser($id_firebase, $username, $password, $first_name, $last_name, $email, $wa_number, $id_role, $profile_picture)
    {
        $this->db->query('INSERT INTO users (id_firebase, username, password, first_name, last_name, email, wa_number, id_role, profile_picture) VALUES 
                          (:id_firebase, :username, :password, :first_name, :last_name, :email, :wa_number, :id_role, :profile_picture)');
        $this->db->bind(':id_firebase', $id_firebase, PDO::PARAM_STR);
        $this->db->bind(':username', $username);
        $this->db->bind(':password', $password);
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
        $this->db->bind(':id_user', $id_user, PDO::PARAM_INT);

        return $this->db->single();
    }

    // Get a single user by Firebase id
    public function getUserByFireBaseId($id_firebase)
    {
        $this->db->query('SELECT * FROM users WHERE id_firebase = :id_firebase');
        $this->db->bind(':id_firebase', $id_firebase, PDO::PARAM_STR);

        return $this->db->single();
    }
    

    // Get all users
    public function getAllUsers()
    {
        $this->db->query('SELECT * FROM users');
        return $this->db->resultSet();
    }

    public function updateUser($id_user, $username, $first_name, $last_name, $email, $wa_number, $profile_picture)
    {
        $query = 'UPDATE users SET 
            username = :username, 
            first_name = :first_name, 
            last_name = :last_name, 
            email = :email, 
            wa_number = :wa_number';

        if ($profile_picture) {
            $query .= ', profile_picture = :profile_picture';
        }

        $query .= ' WHERE id_user = :id_user';

        $this->db->query($query);

        $this->db->bind(':username', $username);
        $this->db->bind(':first_name', $first_name);
        $this->db->bind(':last_name', $last_name);
        $this->db->bind(':email', $email);
        $this->db->bind(':wa_number', $wa_number);

        if ($profile_picture) {
            $this->db->bind(':profile_picture', $profile_picture);
        }

        $this->db->bind(':id_user', $id_user);

        return $this->db->execute();
    }

    

    // Delete a user
    public function deleteUser($id_user)
    {
        $this->db->query('DELETE FROM users WHERE id_user = :id_user');
        $this->db->bind(':id_user', $id_user, PDO::PARAM_INT);

        return $this->db->execute();
    }

    // Get a single user by criteria
    public function getUserByCriteria($criteria)
    {
        $query = 'SELECT * FROM users WHERE ';
        $conditions = [];
        $params = [];
        $bindParams = [
            'id_firebase' => PDO::PARAM_STR,
            'username' => PDO::PARAM_STR,
            'email' => PDO::PARAM_STR,
            'wa_number' => PDO::PARAM_STR,
            'id_user' => PDO::PARAM_INT,
            'id_firebase' => PDO::PARAM_STR,
            'first_name' => PDO::PARAM_STR,
            'last_name' => PDO::PARAM_STR,
            'id_role' => PDO::PARAM_INT,
            'profile_picture' => PDO::PARAM_STR,
        ];

        foreach ($criteria as $column => $value) {
            if (array_key_exists($column, $bindParams)) {
                $conditions[] = "$column = :$column";
                $params[":$column"] = $value;
            }
        }

        $query .= implode(' AND ', $conditions);

        $this->db->query($query);

        foreach ($params as $param => $value) {
            $this->db->bind($param, $value, $bindParams[ltrim($param, ':')]);
        }

        return $this->db->single();
    }
}
