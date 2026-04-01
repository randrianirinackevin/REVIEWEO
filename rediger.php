<?php
require_once __DIR__ . '/vendor/autoload.php';
session_start();
$db = (new App\Config\Database())->getConnection();

// Protection : il faut être connecté pour accéder à cette page
if (!isset($_SESSION['user_id'])) {
    header("Location: connexion.php");
    exit();
}

// On vérifie si l'utilisateur a le droit de rédiger (Role 1 ou 2)
$user_role = $_SESSION['role'] ?? 0;
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Revieweo | Rédiger une critique</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="css/style.css">
</head>
<body class="bg-dark text-white">
    <nav class="navbar navbar-expand-lg navbar-dark fixed-top" style="background: rgba(0,0,0,0.9); backdrop-filter: blur(10px);">
        <div class="container">
            <a class="navbar-brand fw-bold" href="index.php">REVIEWEO <span class="text-neon-green">GAMES</span></a>
        </div>
    </nav>

    <div class="container py-5 mt-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="glass-card border-blue p-4">
                    <h2 class="text-neon-blue mb-4"><i class="fas fa-pen-nib me-2"></i>Rédiger une critique</h2>

                    <?php if ($user_role >= 1): ?>
                        <form action="critique_action.php" method="POST">
                            
                            <div class="mb-3">
                                <label class="text-white-50 small fw-bold">NOM DU JEU</label>
                                <input type="text" name="titre" class="form-control bg-dark text-white border-secondary" placeholder="Ex: Elden Ring, Ghost of Tsushima..." required>
                            </div>

                            <div class="mb-3">
                                <label class="text-white-50 small fw-bold">NOTE (Sur 20)</label>
                                <input type="number" name="note" min="0" max="20" class="form-control bg-dark text-white border-secondary" placeholder="18" required>
                            </div>

                            <div class="mb-3">
                                <label class="text-white-50 small fw-bold">GENRES / CATÉGORIES</label>
                                <div class="d-flex flex-wrap gap-3 p-3 border border-secondary rounded bg-dark">
                                    <?php
                                    $categories = $db->query("SELECT * FROM categorie ORDER BY nom ASC")->fetchAll();
                                    if (empty($categories)) {
                                        echo '<p class="text-muted small mb-0">Aucune catégorie disponible.</p>';
                                    }
                                    foreach($categories as $cat): ?>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="categories[]" value="<?= $cat['id'] ?>" id="cat<?= $cat['id'] ?>">
                                            <label class="form-check-label text-white-50 small" for="cat<?= $cat['id'] ?>">
                                                <?= htmlspecialchars($cat['nom']) ?>
                                            </label>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>

                            <div class="mb-4">
                                <label class="text-white-50 small fw-bold">VOTRE AVIS</label>
                                <textarea name="contenu" rows="6" class="form-control bg-dark text-white border-secondary" placeholder="Partagez votre expérience de jeu..." required></textarea>
                            </div>

                            <div class="row g-3">
                                <div class="col-md-6">
                                    <a href="voir_critiques.php" class="btn btn-outline-secondary w-100 fw-bold py-2">
                                        <i class="fas fa-times me-2"></i>ANNULER
                                    </a>
                                </div>
                                <div class="col-md-6">
                                    <button type="submit" class="btn btn-neon-blue w-100 fw-bold py-2">
                                        <i class="fas fa-paper-plane me-2"></i>PUBLIER
                                    </button>
                                </div>
                            </div>
                        </form>
                    <?php else: ?>
                        <div class="text-center py-4">
                            <i class="fas fa-lock text-warning fa-3x mb-3"></i>
                            <h4 class="text-white">Accès restreint</h4>
                            <p class="text-white-50">Seuls les membres ayant le rôle "Critique" ou "Admin" peuvent publier.</p>
                            <a href="index.php" class="btn btn-outline-light mt-2">Retour à l'accueil</a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</body>
</html>