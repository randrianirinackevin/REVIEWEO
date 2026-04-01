<?php
require_once __DIR__ . '/vendor/autoload.php';
session_start();
// Seuls les critiques (role 1) et les admins (role 2) peuvent supprimer une critique
if (isset($_GET['id']) && isset($_SESSION['user_id'])) {
    $db = (new App\Config\Database())->getConnection();
    
    // Sécurité : on vérifie que la critique appartient bien à l'utilisateur
    $stmt = $db->prepare("DELETE FROM critique WHERE id = :id AND id_user = :id_user");
    $stmt->execute([
        ':id' => $_GET['id'],
        ':id_user' => $_SESSION['user_id']
    ]);
}

header("Location: mon_tableau_de_bord.php"); // On redirige vers son espace
exit();