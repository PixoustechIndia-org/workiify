<?php
class Admin_model {
    private $db;

    public function __construct() {
        $this->db = new Database();
    }

    public function verify($username, $password) {
        $this->db->query('SELECT * FROM admin_users WHERE username = :u');
        $this->db->bind(':u', $username);
        $user = $this->db->single();
        if ($user && password_verify($password, $user->password_hash)) {
            return $user;
        }
        return false;
    }
}
