<?php
namespace App\Models;

use PDO;

class User {
    private $conn;
    private $table_name = "user"; 

    public $id;
    public $pseudo;
    public $email;
    public $password;
    public $role;

    public function __construct($db) {
        $this->conn = $db;
    }

    // La méthode register insère un nouvel utilisateur dans la base de données avec un mot de passe haché et un rôle par défaut de 0 (utilisateur standard)
    public function register() {
        $query = "INSERT INTO " . $this->table_name . " 
                  SET pseudo = :pseudo, email = :email, password = :password, role = :role";

        $stmt = $this->conn->prepare($query);

        $this->password = password_hash($this->password, PASSWORD_BCRYPT);
        $this->role = 0;

        $stmt->bindParam(":pseudo", $this->pseudo);
        $stmt->bindParam(":email", $this->email);
        $stmt->bindParam(":password", $this->password);
        $stmt->bindParam(":role", $this->role);

        return $stmt->execute();
    }


    // La méthode login vérifie les informations d'identification de l'utilisateur, récupère son rôle et démarre une session si l'authentification est réussie
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
                $this->role = $row['role'];
                return true;
            }
        }
        return false;
    }
}