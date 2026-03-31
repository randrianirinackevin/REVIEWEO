<?php
session_start();

// On vide toutes les variables de session
$_SESSION = array();

// On détruit la session côté serveur
session_destroy();

// On redirige vers l'accueil
header("Location: index.php");
exit();