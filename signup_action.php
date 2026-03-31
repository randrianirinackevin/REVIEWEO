<?php
// On charge l'autoloader pour accéder aux classes dans /src
require_once __DIR__ . '/vendor/autoload.php';

use App\Controllers\AuthController;

// On vérifie que des données ont bien été envoyées en POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $auth = new AuthController();
    
    // On passe tout le tableau $_POST (pseudo, email, password) au contrôleur
    $auth->register($_POST);
} else {
    // Si quelqu'un tente d'accéder au fichier en direct, on le renvoie à l'inscription
    header("Location: inscription.php");
    exit();
}