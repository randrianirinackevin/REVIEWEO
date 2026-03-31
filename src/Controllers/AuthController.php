<?php
namespace App\Controllers;

use App\Config\Database;
use App\Models\User;

class AuthController {

    private $db;

    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
    }

    /**
     * Gère l'inscription
     */
    public function register($data) {
        $user = new User($this->db);
        $user->pseudo = $data['pseudo'];
        $user->email = $data['email'];
        $user->password = $data['password'];

        if($user->register()) {
            header("Location: login.php?success=registered");
        } else {
            header("Location: signup.php?error=failed");
        }
    }

    /**
     * Gère la connexion
     */
    public function login($data) {
        $user = new User($this->db);
        $user->email = $data['email'];
        $user->password = $data['password'];

        if($user->login()) {
            session_start();
            $_SESSION['user_id'] = $user->id;
            $_SESSION['pseudo'] = $user->pseudo;
            header("Location: index.php");
        } else {
            header("Location: login.php?error=invalid");
        }
    }
}