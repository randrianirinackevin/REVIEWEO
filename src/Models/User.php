<?php
namespace App\Models;

use PDO;

class User {
    private $conn;
    private $table_name = "User";

    // Propriétés conformes au schéma imposé 
    public $id;
    public $pseudo;
    public $email;
    public $password;
    public $role; // 0: Utilisateur, 1: Critique, 2: Admin 

    public function __construct($db) {
        $this->conn = $db;
    }

    /**
     * MÉTHODE SIGN UP (Inscription)
     */
    public function register() {
        $query = "INSERT INTO " . $this->table_name . " 
                  SET pseudo = :pseudo, email = :email, password = :password, role = :role";

        $stmt = $this->conn->prepare($query);

        // Hachage du mot de passe pour la sécurité
        $this->password = password_hash($this->password, PASSWORD_BCRYPT);
        
        // Par défaut, un nouvel inscrit est un simple "Utilisateur" (role 0)
        $this->role = 0;

        $stmt->bindParam(":pseudo", $this->pseudo);
        $stmt->bindParam(":email", $this->email);
        $stmt->bindParam(":password", $this->password);
        $stmt->bindParam(":role", $this->role);

        return $stmt->execute();
    }

    /**
     * MÉTHODE LOGIN (Connexion) - Déjà créée
     */
    public function login() {
        $query = "SELECT id, pseudo, password, role FROM " . $this->table_name . " WHERE email = :email LIMIT 1";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":email", $this->email);
        $stmt->execute();

        if($stmt->rowCount() > 0) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            if(password_verify($this->password, $row['password'])) {
                $this->id = $row['id'];
                $this->pseudo = $row['pseudo'];
                $this->role = $row['role']; // On récupère aussi le rôle pour les accès 
                return true;
            }
        }
        return false;
    }
}