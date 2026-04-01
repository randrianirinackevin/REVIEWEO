<?php
require_once __DIR__ . '/vendor/autoload.php';
session_start();

if (!isset($_SESSION['role']) || $_SESSION['role'] < 2) { exit("Accès refusé"); } // Seuls les admins (role 2) peuvent accéder à cette page

$db = (new App\Config\Database())->getConnection(); 

// Mettre à jour le rôle d'un utilisateur
if (isset($_POST['action']) && $_POST['action'] === 'update_role') {
    $stmt = $db->prepare("UPDATE user SET role = :role WHERE id = :id");
    $stmt->execute(['role' => $_POST['new_role'], 'id' => $_POST['user_id']]);
    header("Location: admin.php");
    exit();
}

// Supprimer une critique 
if (isset($_GET['delete_critique'])) {
    $stmt = $db->prepare("DELETE FROM critique WHERE id = :id");
    $stmt->execute(['id' => $_GET['delete_critique']]);
    header("Location: admin.php");
    exit();
}

// Épingler / Désépingler une critique
if (isset($_GET['pin_critique'])) {
    // On utilise 1 - pinned pour basculer entre 0 et 1 automatiquement
    $stmt = $db->prepare("UPDATE critique SET pinned = 1 - pinned WHERE id = :id");
    $stmt->execute(['id' => $_GET['pin_critique']]);
    header("Location: admin.php");
    exit();
}

// Supprimer un utilisateur
if (isset($_GET['delete_user'])) {
    $stmt = $db->prepare("DELETE FROM user WHERE id = :id");
    $stmt->execute(['id' => $_GET['delete_user']]);
    header("Location: admin.php");
    exit();
}