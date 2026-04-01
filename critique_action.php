<?php
session_start();
require_once __DIR__ . '/vendor/autoload.php';

// 1. Vérifier s'il est connecté
if (!isset($_SESSION['user_id'])) {
    die("Accès refusé : Connectez-vous.");
}

// 2. VÉRIFIER LE RÔLE (0 = User, 1 = Critique, 2 = Admin)
// On bloque si le rôle est inférieur à 1
if (!isset($_SESSION['role']) || $_SESSION['role'] < 1) {
    die("Accès refusé : Vous n'avez pas l'autorisation de publier des critiques.");
}

$db = (new App\Config\Database())->getConnection();
// ... la suite de ton code d'insertion ...

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    try {
        $db->beginTransaction();

        $stmt = $db->prepare("INSERT INTO critique (titre, contenu, note, date_creation, id_user) VALUES (?, ?, ?, NOW(), ?)");
        $stmt->execute([$_POST['titre'], $_POST['contenu'], $_POST['note'], $_SESSION['user_id']]);
        
        $id_critique = $db->lastInsertId();

        if (!empty($_POST['categories'])) {
            $stmt_cat = $db->prepare("INSERT INTO critique_categorie (id_critique, id_categorie) VALUES (?, ?)");
            foreach ($_POST['categories'] as $id_cat) {
                $stmt_cat->execute([$id_critique, $id_cat]);
            }
        }

        $db->commit();
        header("Location: voir_critiques.php");
    } catch (Exception $e) {
        $db->rollBack();
        die("Erreur : " . $e->getMessage());
    }
}