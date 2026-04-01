<?php
require_once __DIR__ . '/vendor/autoload.php';
session_start();

// Active l'affichage des erreurs pour le débogage
ini_set('display_errors', 1);
error_reporting(E_ALL);

// 1. Vérification de la connexion
if (!isset($_SESSION['user_id'])) {
    die("Erreur : Vous devez être connecté pour publier une critique.");
}

$db = (new App\Config\Database())->getConnection();

// 2. Vérification de la soumission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // Récupération et nettoyage des données
    $titre    = trim($_POST['titre'] ?? '');
    $note     = intval($_POST['note'] ?? 0);
    $contenu  = trim($_POST['contenu'] ?? '');
    $id_user  = $_SESSION['user_id'];
    $categories = $_POST['categories'] ?? []; // Tableau d'IDs de catégories

    // Validation simple
    if (empty($titre) || empty($contenu)) {
        die("Erreur : Le titre et le contenu ne peuvent pas être vides.");
    }

    try {
        // Début d'une transaction pour s'assurer que tout est enregistré ou rien du tout
        $db->beginTransaction();

        // 3. Insertion de la critique dans la table 'critique'
        $sql = "INSERT INTO critique (titre, contenu, note, date_creation, id_user) 
                VALUES (:titre, :contenu, :note, NOW(), :id_user)";
        $stmt = $db->prepare($sql);
        
        $stmt->execute([
            ':titre'   => $titre,
            ':contenu' => $contenu,
            ':note'    => $note,
            ':id_user' => $id_user
        ]);

        // 4. Récupération de l'ID de la critique que l'on vient d'insérer
        $id_critique = $db->lastInsertId();

        // 5. Insertion des liens dans la table de liaison 'critique_categorie'
        if (!empty($categories) && is_array($categories)) {
            $sql_cat = "INSERT INTO critique_categorie (id_critique, id_categorie) VALUES (:id_crit, :id_cat)";
            $stmt_cat = $db->prepare($sql_cat);

            foreach ($categories as $id_categorie) {
                $stmt_cat->execute([
                    ':id_crit' => $id_critique,
                    ':id_cat'  => intval($id_categorie)
                ]);
            }
        }

        // Si tout est ok, on valide la transaction
        $db->commit();

        // Redirection vers la liste des critiques
        header("Location: voir_critiques.php?success=1");
        exit();

    } catch (PDOException $e) {
        // En cas d'erreur, on annule tout ce qui a été fait dans la transaction
        if ($db->inTransaction()) {
            $db->rollBack();
        }
        die("Erreur lors de l'enregistrement en base de données : " . $e->getMessage());
    }
} else {
    // Si on accède au fichier sans POST
    header("Location: rediger.php");
    exit();
}