<?php
require_once __DIR__ . '/vendor/autoload.php';

use App\Controllers\AuthController;

$auth = new AuthController(); // On crée une instance du contrôleur d'authentification
$auth->login($_POST);// On passe les données du formulaire de connexion au contrôleur pour tenter de connecter l'utilisateur