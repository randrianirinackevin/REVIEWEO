<?php
namespace App\Models;



class Critique {
    private $conn;
    private $table_name = "critique"; 

    public $id;
    public $titre;
    public $contenu;
    public $note;
    public $date_creation;
    public $id_user;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function create() {
        $query = "INSERT INTO " . $this->table_name . " 
                  SET titre=:titre, contenu=:contenu, note=:note, id_user=:id_user, date_creation=NOW()"; // date_creation est gérée par la base de données avec NOW()

        $stmt = $this->conn->prepare($query); 

        $stmt->bindParam(":titre", $this->titre);   
        $stmt->bindParam(":contenu", $this->contenu);
        $stmt->bindParam(":note", $this->note);
        $stmt->bindParam(":id_user", $this->id_user);

        return $stmt->execute();
    }

    // La méthode readAll récupère toutes les critiques avec le pseudo de l'auteur, triées par date de création décroissante
    public function readAll() {
        
        $query = "SELECT c.*, u.pseudo FROM " . $this->table_name . " c 
                  JOIN user u ON c.id_user = u.id 
                  ORDER BY c.date_creation DESC";
        
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }
}