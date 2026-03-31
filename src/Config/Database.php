<?php
namespace App\Config; // AJOUT INDISPENSABLE

use PDO; // Importation de la classe PDO native
use PDOException;

class Database {
    private $host = "localhost";
    private $db_name = "revieweo";
    private $username = "root";
    private $password = "";
    public $conn;

    public function getConnection() {
        $this->conn = null;
        try {
            // Ajout du charset directement dans le DSN pour plus de sécurité
            $this->conn = new PDO("mysql:host=" . $this->host . ";dbname=" . $this->db_name . ";charset=utf8", $this->username, $this->password);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch(PDOException $exception) {
            echo "Erreur de connexion : " . $exception->getMessage();
        }
        return $this->conn;
    }
}