<?php
require_once __DIR__ . '/vendor/autoload.php';
session_start();

// 1. Vérification de la connexion
if (!isset($_SESSION['user_id'])) {
    header("Location: connexion.php");
    exit();
}

// 2. Vérification du Rôle (doit être 1 ou 2)
if (!isset($_SESSION['role']) || $_SESSION['role'] < 1) {
    header("Location: voir_critiques.php?error=access_denied");
    exit();
}

$db = (new App\Config\Database())->getConnection();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Revieweo | Rédiger</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="css/style.css">
</head>
<body class="bg-dark text-white">
    <div class="container py-5 mt-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="glass-card border-blue p-4">
                    <h2 class="text-neon-blue mb-4"><i class="fas fa-pen-nib me-2"></i>Nouvelle Critique</h2>

                    <form action="critique_action.php" method="POST">
                        <div class="mb-3">
                            <label class="text-white-50 small fw-bold">NOM DU JEU</label>
                            <input type="text" name="titre" class="form-control bg-dark text-white border-secondary" required>
                        </div>

                        <div class="mb-3">
                            <label class="text-white-50 small fw-bold">NOTE /20</label>
                            <input type="number" name="note" min="0" max="20" class="form-control bg-dark text-white border-secondary" required>
                        </div>

                        <div class="mb-3">
                            <label class="text-white-50 small fw-bold">CATÉGORIES</label>
                            <div class="d-flex flex-wrap gap-3 p-3 border border-secondary rounded bg-dark">
                                <?php
                                $categories = $db->query("SELECT * FROM categorie ORDER BY nom ASC")->fetchAll();
                                foreach($categories as $cat): ?>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="categories[]" value="<?= $cat['id'] ?>" id="cat<?= $cat['id'] ?>">
                                        <label class="form-check-label text-white-50 small" for="cat<?= $cat['id'] ?>"><?= htmlspecialchars($cat['nom']) ?></label>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="text-white-50 small fw-bold">VOTRE AVIS</label>
                            <textarea name="contenu" rows="5" class="form-control bg-dark text-white border-secondary" required></textarea>
                        </div>

                        <div class="row g-3">
                            <div class="col-md-6">
                                <a href="index.php" class="btn btn-outline-secondary w-100">ANNULER</a>
                            </div>
                            <div class="col-md-6">
                                <button type="submit" class="btn btn-neon-blue w-100">PUBLIER</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>
</html>