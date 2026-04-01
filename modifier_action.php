<?php
require_once __DIR__ . '/vendor/autoload.php';
session_start();

// Seuls les utilisateurs connectés peuvent modifier une critique
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_SESSION['user_id'])) {
    $db = (new App\Config\Database())->getConnection();
    
    $id = $_POST['id'];
    $note = $_POST['note'];
    $contenu = $_POST['contenu'];
    $id_user = $_SESSION['user_id'];

    // Sécurité : on ajoute "AND id_user = :id_user" pour empêcher le piratage via POST
    $sql = "UPDATE critique SET note = :note, contenu = :contenu WHERE id = :id AND id_user = :id_user";
    $stmt = $db->prepare($sql);
    
    $success = $stmt->execute([
        ':note'    => $note,
        ':contenu' => $contenu,
        ':id'      => $id,
        ':id_user' => $id_user
    ]);

    if ($success) {
        header("Location: voir_critiques.php?msg=modified");
    } else {
        echo "Erreur lors de la mise à jour.";
    }
}