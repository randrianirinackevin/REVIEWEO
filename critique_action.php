<?php
require_once __DIR__ . '/vendor/autoload.php';
use App\Controllers\CritiqueController;

session_start();

// Sécurité : on vérifie que l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
    header("Location: connexion.php?error=not_connected");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $critiqueCtrl = new CritiqueController();
    // On ajoute l'ID de l'utilisateur de la session aux données du formulaire
    $data = $_POST;
    $data['id_user'] = $_SESSION['user_id'];
    
    $critiqueCtrl->store($data);
}