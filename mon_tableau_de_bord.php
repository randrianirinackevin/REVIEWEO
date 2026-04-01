<?php
require_once __DIR__ . '/vendor/autoload.php';
session_start();
if (!isset($_SESSION['user_id'])) { header("Location: connexion.php"); exit(); }

$db = (new App\Config\Database())->getConnection();
$user_id = $_SESSION['user_id'];

// 1. Récupérer MES critiques
$sql_mes_critiques = "SELECT * FROM critique WHERE id_user = :user_id ORDER BY date_creation DESC";
$stmt1 = $db->prepare($sql_mes_critiques);
$stmt1->execute([':user_id' => $user_id]);
$mes_critiques = $stmt1->fetchAll(PDO::FETCH_ASSOC);

// 2. Récupérer les critiques que J'AI LIKÉES 
$sql_likes = "SELECT c.*, u.pseudo as auteur 
              FROM `like` l
              JOIN critique c ON l.id_critique = c.id
              JOIN user u ON c.id_user = u.id
              WHERE l.id_user = :user_id";
$stmt2 = $db->prepare($sql_likes);
$stmt2->execute([':user_id' => $user_id]);
$critiques_likees = $stmt2->fetchAll(PDO::FETCH_ASSOC); // Contient les critiques que l'utilisateur a likées, avec le pseudo de l'auteur de chaque critique
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Mon Espace | Revieweo</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="css/style.css">
</head>
<body class="bg-dark text-white">
    <div class="container mt-5">
        <h2 class="fw-bold mb-5">BIENVENUE, <span class="text-neon-blue"><?= strtoupper($_SESSION['pseudo']) ?></span></h2>

        <div class="row g-5">
            <div class="col-lg-6">
                <h4 class="text-neon-green mb-4"><i class="fas fa-pen-nib me-2"></i>Mes Critiques</h4>
                <?php if(empty($mes_critiques)): ?>
                    <p class="text-white-50">Vous n'avez pas encore écrit d'avis. <a href="rediger.php">Rédiger ?</a></p>
                <?php else: ?>
                    <?php foreach($mes_critiques as $c): ?>
                        <div class="glass-card border-blue p-3 mb-3 d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="mb-0 fw-bold"><?= htmlspecialchars($c['titre']) ?></h6>
                                <span class="badge bg-info text-dark"><?= $c['note'] ?>/20</span>
                            </div>
                            <div class="d-flex gap-2">
                                <a href="modifier_critique.php?id=<?= $c['id'] ?>" class="btn btn-sm btn-outline-warning"><i class="fas fa-edit"></i></a>
                                <a href="supprimer_critique.php?id=<?= $c['id'] ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Supprimer ?')"><i class="fas fa-trash"></i></a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>

            <div class="col-lg-6">
                <h4 class="text-neon-red mb-4"><i class="fas fa-heart me-2"></i>Mes Coups de Cœur</h4>
                <?php if(empty($critiques_likees)): ?>
                    <p class="text-white-50">Aucun like pour le moment. Allez voir les <a href="voir_critiques.php">avis communauté</a> !</p>
                <?php else: ?>
                    <?php foreach($critiques_likees as $l): ?>
                        <div class="glass-card border-red p-3 mb-3">
                            <div class="d-flex justify-content-between">
                                <h6 class="mb-0 fw-bold"><?= htmlspecialchars($l['titre']) ?></h6>
                                <a href="like_action.php?id=<?= $l['id'] ?>" class="text-danger"><i class="fas fa-heart"></i></a>
                            </div>
                            <small class="text-white-50">Par @<?= htmlspecialchars($l['auteur']) ?></small>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
        
        <div class="text-center mt-5">
            <a href="index.php" class="btn btn-outline-light">Retour à l'accueil</a>
        </div>
    </div>
</body>
</html>