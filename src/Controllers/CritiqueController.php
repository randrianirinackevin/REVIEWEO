<?php
namespace App\Controllers;

use App\Config\Database;
use App\Models\Critique;

class CritiqueController {

    private $db;

    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
    }

    /**
     * Affiche toutes les critiques (pour la page d'accueil)
     */
    public function index() {
        $critiqueModel = new Critique($this->db);
        return $critiqueModel->readAll(); // Retourne le statement PDO avec toutes les critiques
    }

    /**
     * Enregistre une nouvelle critique en base de données
     */
    public function store($data) {
        // On vérifie que l'utilisateur est connecté via la session
        if (!isset($_SESSION['user_id'])) {
            header("Location: login.php");
            exit();
        }

        $critique = new Critique($this->db);
        
        // On remplit l'objet avec les données du formulaire
        $critique->titre = $data['titre'];
        $critique->contenu = $data['contenu'];
        $critique->note = $data['note'];
        $critique->id_user = $_SESSION['user_id']; // L'auteur est l'utilisateur connecté

        if ($critique->create()) {
            header("Location: index.php?success=1");
        } else {
            header("Location: add_critique.php?error=1");
        }
        exit();
    }
}