<?php
require_once __DIR__ . '/vendor/autoload.php';
session_start();

// Connexion à la base de données
$db = (new App\Config\Database())->getConnection();

// On récupère l'ID de l'utilisateur s'il est connecté
$user_id = $_SESSION['user_id'] ?? 0;

// 1. Récupérer toutes les critiques avec le pseudo de l'auteur, les catégories associées, le nombre total de likes et si l'utilisateur connecté a liké chaque critique
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
$critiques = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Revieweo | Avis de la Communauté</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="css/style.css">
    <style>
        .glass-card {
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(10px);
            border-radius: 15px;
            transition: transform 0.3s ease;
        }
        .glass-card:hover {
            transform: translateY(-5px);
        }
        .text-neon-blue { color: #00d4ff; text-shadow: 0 0 10px rgba(0,212,255,0.5); }
        .text-neon-green { color: #39ff14; text-shadow: 0 0 10px rgba(57,255,20,0.5); }
        .border-blue { border: 1px solid rgba(0,212,255,0.3); }
    </style>
</head>
<body class="bg-dark text-white">

    <nav class="navbar navbar-expand-lg navbar-dark sticky-top" style="background: rgba(0,0,0,0.9); border-bottom: 1px solid #333;">
        <div class="container">
            <a class="navbar-brand fw-bold" href="index.php">REVIEWEO <span class="text-neon-green">GAMES</span></a>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto gap-3">
                    <li class="nav-item"><a class="nav-link" href="index.php">Accueil</a></li>
                    <li class="nav-item"><a class="nav-link active text-neon-blue" href="voir_critiques.php">Avis Communauté</a></li>
                    <?php if(isset($_SESSION['user_id'])): ?>
                        <li class="nav-item">
                            <a href="mon_tableau_de_bord.php" class="nav-link text-neon-green">
                                <i class="fas fa-user-circle"></i> Mon Profil
                            </a>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container mt-5 pt-4">
        <div class="text-center mb-5">
            <h2 class="display-5 fw-bold">AVIS DE LA <span class="text-neon-blue">COMMUNAUTÉ</span></h2>
            <p class="text-white-50">Découvrez ce que les autres joueurs pensent de leurs jeux préférés.</p>
        </div>

        <div class="row">
    <?php if (empty($critiques)): ?>
        <div class="col-12 text-center py-5">
            <p class="text-white-50">Aucune critique n'a été publiée pour le moment.</p>
        </div>
    <?php else: ?>
        <?php foreach ($critiques as $c): ?>
            <div class="col-md-6 mb-4">
                <div class="glass-card border-blue p-4 h-100 <?= $c['pinned'] ? 'border-warning' : '' ?>">
                    
                    <?php if (isset($c['pinned']) && $c['pinned'] == 1): ?>
                        <div class="text-warning small fw-bold mb-2">
                            <i class="fas fa-thumbtack me-1"></i> À LA UNE
                        </div>
                    <?php endif; ?>

                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div>
                            <h4 class="text-white fw-bold mb-0"><?= htmlspecialchars($c['titre']) ?></h4>
                            <small class="text-neon-green">Par @<?= htmlspecialchars($c['pseudo'] ?? 'Anonyme') ?></small>
                        </div>
                        <div class="badge bg-dark border border-info fs-5 text-neon-blue">
                            <?= $c['note'] ?>/20
                        </div>
                    </div>

                    <p class="text-white-50 flex-grow-1" style="white-space: pre-line;">
                        <?= htmlspecialchars($c['contenu']) ?>
                    </p>

                    <div class="mt-4 pt-3 border-top border-secondary d-flex justify-content-between align-items-center">
                        <span class="small text-muted italic">Le <?= date('d/m/Y', strtotime($c['date_creation'])) ?></span>
                        
                        <div class="like-section">
                            <?php if(isset($_SESSION['user_id'])): ?>
                                <a href="like_action.php?id=<?= $c['id'] ?>" class="text-decoration-none">
                                    <i class="<?= ($c['user_liked'] > 0) ? 'fas' : 'far' ?> fa-heart text-danger fs-5"></i>
                                    <span class="text-white ms-1"><?= $c['total_likes'] ?></span>
                                </a>
                            <?php else: ?>
                                <div class="text-white-50 small">
                                    <i class="far fa-heart"></i> <?= $c['total_likes'] ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?> <?php endif; ?> </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>