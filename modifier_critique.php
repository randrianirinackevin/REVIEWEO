<?php
require_once __DIR__ . '/vendor/autoload.php';
session_start();
$db = (new App\Config\Database())->getConnection();

if (!isset($_SESSION['user_id'])) { header("Location: connexion.php"); exit(); }

$id_critique = $_GET['id'] ?? 0;

// On récupère la critique ET on vérifie qu'elle appartient bien à l'utilisateur connecté
$stmt = $db->prepare("SELECT * FROM critique WHERE id = :id AND id_user = :id_user");
$stmt->execute(['id' => $id_critique, 'id_user' => $_SESSION['user_id']]);
$critique = $stmt->fetch();

if (!$critique) {
    die("Erreur : Cette critique n'existe pas ou vous n'avez pas le droit de la modifier.");
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Modifier ma critique</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
</head>
<body class="bg-dark text-white">
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="glass-card border-blue p-4">
                    <h3 class="text-neon-blue mb-4">MODIFIER L'AVIS : <?= htmlspecialchars($critique['titre']) ?></h3>
                    
                    <form action="modifier_action.php" method="POST">
                        <input type="hidden" name="id" value="<?= $critique['id'] ?>">
                        
                        <div class="mb-3">
                            <label class="form-label small fw-bold">NOTE /20</label>
                            <input type="number" name="note" class="form-control" min="0" max="20" value="<?= $critique['note'] ?>" required>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label small fw-bold">MON AVIS</label>
                            <textarea name="contenu" class="form-control" rows="6" required><?= htmlspecialchars($critique['contenu']) ?></textarea>
                        </div>
                        
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-neon-blue w-100 fw-bold">ENREGISTRER</button>
                            <a href="voir_critiques.php" class="btn btn-outline-secondary">ANNULER</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>
</html>