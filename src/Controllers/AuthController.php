<?php
namespace App\Controllers;

use App\Config\Database;
use App\Models\User;

class AuthController {
    private $db;


    // Le constructeur initialise la connexion à la base de données
    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
    }


    // La méthode register reçoit les données du formulaire d'inscription, crée un nouvel utilisateur et tente de l'enregistrer
    public function register($data) {
        $user = new User($this->db);
        $user->pseudo = $data['pseudo'];
        $user->email = $data['email'];
        $user->password = $data['password'];

        if($user->register()) {
        // redirection vers connexion.php
            header("Location: connexion.php?success=registered");
        } else {
            // redirection vers inscription.php
            header("Location: inscription.php?error=failed");
        }
        exit();
    }


    // La méthode login reçoit les données du formulaire de connexion, tente d'authentifier l'utilisateur et démarre une session
    public function login($data) {
        $user = new User($this->db);
        $user->email = $data['email'];
        $user->password = $data['password'];

        if($user->login()) {
            // On vérifie si une session n'est pas déjà lancée
            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }
            $_SESSION['user_id'] = $user->id;
            $_SESSION['pseudo'] = $user->pseudo;
            $_SESSION['role'] = $user->role;
            header("Location: index.php");
        } else {
            // redirection vers connexion.php
            header("Location: connexion.php?error=invalid");
        }
        exit();
    }
}