<?php
namespace App\Models;



class Critique {
    private $conn;
    private $table_name = "Critique";

    // Propriétés conformes au schéma 
    public $id;
    public $titre;
    public $contenu;
    public $note;
    public $date_creation;
    public $id_user;

    public function __construct($db) {
        $this->conn = $db;
    }

    /**
     * CREATE : Publier une critique 
     */
    public function create() {
        $query = "INSERT INTO " . $this->table_name . " 
                  SET titre=:titre, contenu=:contenu, note=:note, id_user=:id_user, date_creation=NOW()";

        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(":titre", $this->titre);
        $stmt->bindParam(":contenu", $this->contenu);
        $stmt->bindParam(":note", $this->note);
        $stmt->bindParam(":id_user", $this->id_user);

        return $stmt->execute();
    }

    /**
     * READ : Lister toutes les critiques 
     */
    public function readAll() {
        $query = "SELECT c.*, u.pseudo FROM " . $this->table_name . " c 
                  JOIN User u ON c.id_user = u.id 
                  ORDER BY c.date_creation DESC";
        
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }
}