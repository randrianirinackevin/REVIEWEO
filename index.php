<?php
session_start();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Revieweo | Accueil</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

    <nav class="navbar navbar-expand-lg navbar-dark fixed-top" style="background: rgba(0,0,0,0.9); backdrop-filter: blur(10px);">
        <div class="container">
            <a class="navbar-brand fw-bold" href="index.php">REVIEWEO <span class="text-neon-green">GAMES</span></a>
            
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto gap-3 align-items-center">
    <li class="nav-item"><a class="nav-link active" href="index.php">Accueil</a></li>
    <li class="nav-item"><a class="nav-link" href="rediger.php">Critiques</a></li>
    <li class="nav-item"><a class="nav-link" href="voir_critiques.php">Avis Communauté</a></li>
    <li class="nav-item"><a class="nav-link" href="tendances.php">Tendances</a></li>
    
    <?php if(isset($_SESSION['user_id'])): ?>
        <?php if(isset($_SESSION['role']) && $_SESSION['role'] == 2): ?>
            <li class="nav-item">
                <a href="admin.php" class="btn btn-sm btn-outline-danger px-3 me-2">
                    <i class="fas fa-shield-alt"></i> Console Admin
                </a>
            </li>
        <?php endif; ?>

        <li class="nav-item">
            <a href="mon_tableau_de_bord.php" class="nav-link text-neon-green">
                <i class="fas fa-user-circle"></i> Mon Profil (<?= htmlspecialchars($_SESSION['pseudo']) ?>)
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link btn-neon-red px-3" href="logout.php">Déconnexion</a>
        </li>
    <?php else: ?>
        <li class="nav-item"><a class="nav-link" href="connexion.php">Connexion</a></li>
        <li class="nav-item"><a class="nav-link btn-neon-blue px-3" href="inscription.php">S'inscrire</a></li>
    <?php endif; ?>
</ul>
            </div>
        </div>
    </nav>

    <header class="hero-section d-flex align-items-center justify-content-center text-center">
        <div class="container position-relative" style="z-index: 2;">
            <h1 class="display-2 fw-bold text-white mb-3">VOTRE UNIVERS <span class="text-neon-blue">GAMING</span></h1>
            <p class="lead text-white-50 mb-4">Découvrez, notez et partagez vos meilleures expériences de jeu.</p>
            <div class="d-flex justify-content-center gap-3">
                <a href="#game-container" class="btn btn-neon-blue btn-lg px-4 fw-bold">DÉCOUVRIR</a>
                <a href="rediger.php" class="btn btn-outline-light btn-lg px-4">RÉDIGER UN AVIS</a>
            </div>
        </div>
    </header>

    <div class="container my-5">
        <div class="row g-4 text-center">
            <div class="col-md-4">
                <div class="glass-card h-100 border-blue p-4">
                    <i class="fas fa-gamepad fa-3x text-neon-blue mb-3"></i>
                    <h4 class="text-white fw-bold">EXPLOREZ</h4>
                    <p class="text-white-50 small">Accédez à une base de données de milliers de jeux via l'API RAWG.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="glass-card h-100 border-red p-4">
                    <i class="fas fa-star fa-3x text-neon-red mb-3"></i>
                    <h4 class="text-white fw-bold">CRITIQUEZ</h4>
                    <p class="text-white-50 small">Notez vos jeux de 0 à 20 et partagez votre avis avec les membres.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="glass-card h-100 border-green p-4">
                    <i class="fas fa-users fa-3x text-neon-green mb-3"></i>
                    <h4 class="text-white fw-bold">COMMUNAUTÉ</h4>
                    <p class="text-white-50 small">Likez les critiques et suivez les tendances des joueurs.</p>
                </div>
            </div>
        </div>

        <div class="d-flex justify-content-between align-items-center mb-4 mt-5 pt-4">
            <h3 class="text-white fw-bold">TOP SÉLECTION <span class="text-neon-blue">POUR VOUS</span></h3>
            <a href="tendances.php" class="text-neon-blue text-decoration-none small">Voir tout <i class="fas fa-chevron-right ms-1"></i></a>
        </div>
        
        <div class="row" id="game-container"></div>
    </div>

    <footer class="bg-dark py-5 border-top border-secondary mt-5">
        <div class="container text-center text-white-50">
            <p class="mb-2 fw-bold text-white">REVIEWEO GAMES</p>
            <p class="small mb-0">&copy; 2026 - Plateforme de critiques gaming. Tous droits réservés.</p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="js/js.js"></script>
</body>
</html>