<?php
require_once __DIR__ . '/vendor/autoload.php';
session_start();

// Si l'utilisateur n'est pas connecté, on l'envoie vers la connexion
if (!isset($_SESSION['user_id'])) {
    header("Location: connexion.php");
    exit();
}

$db = (new App\Config\Database())->getConnection();

$id_critique = $_GET['id'] ?? 0; // Récupère l'ID de la critique depuis l'URL (ex: like_action.php?id=5)
$id_user = $_SESSION['user_id'];// Récupère l'ID de l'utilisateur depuis la session

if ($id_critique > 0) {
    try {
        // 1. On vérifie si l'utilisateur a DÉJÀ liké cette critique
        $checkStmt = $db->prepare("SELECT * FROM `like` WHERE id_user = :id_user AND id_critique = :id_critique");
        $checkStmt->execute([':id_user' => $id_user, ':id_critique' => $id_critique]);
        
        if ($checkStmt->rowCount() > 0) {
            // S'il a déjà liké, on SUPPRIME le like 
            $delStmt = $db->prepare("DELETE FROM `like` WHERE id_user = :id_user AND id_critique = :id_critique");
            $delStmt->execute([':id_user' => $id_user, ':id_critique' => $id_critique]);
        } else {
            // S'il n'a pas liké, on AJOUTE le like
            $insStmt = $db->prepare("INSERT INTO `like` (id_user, id_critique) VALUES (:id_user, :id_critique)");
            $insStmt->execute([':id_user' => $id_user, ':id_critique' => $id_critique]);
        }
    } catch (PDOException $e) {
        die("Erreur avec les likes : " . $e->getMessage());
    }
}

// On renvoie l'utilisateur d'où il vient 
header("Location: voir_critiques.php");
exit();