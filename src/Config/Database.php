<?php
namespace App\Config; 

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
            // Correction : ajout de charset=utf8 pour éviter les problèmes d'encodage
            $this->conn = new PDO("mysql:host=" . $this->host . ";dbname=" . $this->db_name . ";charset=utf8", $this->username, $this->password);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // Mode d'erreur pour les exceptions
        } catch(PDOException $exception) {
            echo "Erreur de connexion : " . $exception->getMessage();
        }
        return $this->conn;
    }
}