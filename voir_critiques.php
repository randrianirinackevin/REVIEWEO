<?php
require_once __DIR__ . '/vendor/autoload.php';
session_start();

$db = (new App\Config\Database())->getConnection();
$user_id = $_SESSION['user_id'] ?? 0;
$user_role = $_SESSION['role'] ?? 0; // On récupère le rôle stocké en session

// Requête pour récupérer les critiques avec les infos nécessaires (auteur, catégories, likes)
$sql = "SELECT c.*, u.pseudo, 
        GROUP_CONCAT(cat.nom SEPARATOR ', ') AS categories_list, 
        (SELECT COUNT(*) FROM `like` WHERE id_critique = c.id) AS total_likes,
        (SELECT COUNT(*) FROM `like` WHERE id_critique = c.id AND id_user = :user_id) AS user_liked
        FROM critique c 
        LEFT JOIN user u ON c.id_user = u.id 
        LEFT JOIN critique_categorie cc ON c.id = cc.id_critique 
        LEFT JOIN categorie cat ON cc.id_categorie = cat.id 
        GROUP BY c.id 
        ORDER BY c.pinned DESC, c.date_creation DESC";

$stmt = $db->prepare($sql);
$stmt->execute([':user_id' => $user_id]);
$critiques = $stmt->fetchAll(); 
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Revieweo | Avis Communauté</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="css/style.css">
</head>
<body class="bg-dark text-white">

<div class="container py-5 mt-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <a href="index.php" class="btn btn-outline-light border-secondary">
            <i class="fas fa-arrow-left me-2"></i>Retour
        </a>
        
        <?php if ($user_role >= 1): ?>
            <a href="rediger.php" class="btn btn-neon-blue">
                <i class="fas fa-pen-nib me-2"></i>Rédiger une critique
            </a>
        <?php endif; ?>
    </div>

    <?php if (isset($_GET['error']) && $_GET['error'] == 'access_denied'): ?>
        <div class="alert alert-danger border-0 bg-danger text-white mb-4">
            <i class="fas fa-exclamation-triangle me-2"></i>
            Accès refusé : Votre rang ne vous permet pas de publier des critiques.
        </div>
    <?php endif; ?>

    <h2 class="text-neon-blue mb-4">Avis de la Communauté</h2>

    <div class="row">
        <?php foreach ($critiques as $c): ?>
            <div class="col-md-6 mb-4">
                <div class="glass-card p-4 <?= $c['pinned'] ? 'border-warning' : '' ?>">
                    <?php if($c['pinned']): ?>
                        <small class="text-warning d-block mb-2"><i class="fas fa-thumbtack"></i> ÉPINGLÉ</small>
                    <?php endif; ?>
                    
                    <div class="d-flex justify-content-between align-items-start">
                        <h4 class="text-white"><?= htmlspecialchars($c['titre']) ?></h4>
                        <span class="badge bg-neon-blue"><?= $c['note'] ?>/20</span>
                    </div>

                    <p class="text-info small mb-3">
                        <i class="fas fa-tags me-1"></i>
                        <?= !empty($c['categories_list']) ? htmlspecialchars($c['categories_list']) : 'Général' ?>
                    </p>

                    <p class="text-white-50"><?= nl2br(htmlspecialchars($c['contenu'])) ?></p>
                    
                    <div class="d-flex justify-content-between align-items-center mt-4 pt-3 border-top border-secondary">
                        <small class="text-muted">Par <?= htmlspecialchars($c['pseudo'] ?? 'Anonyme') ?></small>
                        
                        <button class="btn-like border-0 bg-transparent p-0 d-flex align-items-center" data-id="<?= $c['id'] ?>">
                            <i class="<?= ($c['user_liked'] > 0) ? 'fas' : 'far' ?> fa-heart text-danger fs-5"></i>
                            <span class="like-count text-white ms-2"><?= $c['total_likes'] ?></span>
                        </button>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<script>
// Script pour gérer les likes en AJAX
document.querySelectorAll('.btn-like').forEach(button => {
    button.addEventListener('click', function() {
        const critiqueId = this.getAttribute('data-id'); 
        const icon = this.querySelector('i');
        const countSpan = this.querySelector('.like-count');

        const formData = new FormData();
        formData.append('id', critiqueId);
        
        fetch('like_ajax.php', { method: 'POST', body: formData })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                if (data.liked) {
                    icon.classList.replace('far', 'fas');
                } else {
                    icon.classList.replace('fas', 'far');
                }
                countSpan.textContent = data.total_likes;
            } else {
                alert(data.message || "Erreur de connexion");
            }
        })
        .catch(err => console.error(err));
    });
});
</script>
</body>
</html>