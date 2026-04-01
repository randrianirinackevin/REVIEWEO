<?php
namespace App\Controllers;

use App\Config\Database;
use App\Models\Critique;
use Exception;

class CritiqueController {
    private $db;

    // Le constructeur initialise la connexion à la base de données
    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
    }

    // La méthode store reçoit les données du formulaire de rédaction, crée une nouvelle critique et gère la transaction pour lier la critique à une catégorie
    public function store($data) {
        if (session_status() === PHP_SESSION_NONE) session_start();

        if (!isset($_SESSION['user_id'])) {
            header("Location: connexion.php");
            exit();
        }

        // DEBUT DE LA TRANSACTION
        $this->db->beginTransaction();


        // On utilise un try/catch pour pouvoir faire un rollback en cas d'erreur à n'importe quelle étape
        try {
            $critique = new Critique($this->db);
            $critique->titre = $data['titre'];
            $critique->contenu = $data['contenu'];
            $critique->note = $data['note'];
            $critique->id_user = $_SESSION['user_id'];

            if ($critique->create()) {
                // 1. On récupère l'ID de la critique qui vient d'être créée
                $critiqueId = $this->db->lastInsertId();
                $categorieId = $data['id_categorie'];

                // 2. On insère dans la table de liaison critique_categorie
                $sql = "INSERT INTO critique_categorie (id_critique, id_categorie) VALUES (:crit, :cat)"; 
                $stmt = $this->db->prepare($sql); 
                $stmt->execute([
                    'crit' => $critiqueId,
                    'cat'  => $categorieId
                ]); 

                // 3. Si tout est OK, on valide définitivement
                $this->db->commit();
                header("Location: rediger.php?success=1");
            } else {
                throw new Exception("Erreur lors de la création de la critique");
            }
        } catch (Exception $e) {
            // En cas d'erreur, on annule tout ce qui a été fait dans la transaction
            $this->db->rollBack();
            header("Location: rediger.php?error=" . $e->getMessage());
        }
        exit();
    }
}