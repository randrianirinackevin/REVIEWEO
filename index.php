<?php
session_start();
?>
<ul class="navbar-nav ms-auto gap-3">
    <li class="nav-item"><a class="nav-link" href="index.php">Accueil</a></li>
    
    <?php if(isset($_SESSION['user_id'])): ?>
        <li class="nav-item"><span class="nav-link text-neon-green">Salut, <?= $_SESSION['pseudo'] ?></span></li>
        <li class="nav-item"><a class="nav-link btn-neon-red" href="logout.php">Déconnexion</a></li>
    <?php else: ?>
        <li class="nav-item"><a class="nav-link" href="connexion.php">Connexion</a></li>
        <li class="nav-item"><a class="nav-link btn-neon-blue" href="inscription.php">S'inscrire</a></li>
    <?php endif; ?>
</ul>

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
    <nav class="navbar navbar-expand-lg navbar-dark fixed-top">
    <div class="container">
        <a class="navbar-brand fw-bold" href="index.php">REVIEWEO <span class="text-neon-green">GAMES</span></a>
        <div class="collapse navbar-collapse">
            <ul class="navbar-nav ms-auto gap-3 align-items-center">
                <li class="nav-item"><a class="nav-link" href="index.php">Accueil</a></li>
                <li class="nav-item"><a class="nav-link" href="tendances.php">Tendances</a></li>
                <li class="nav-item"><a class="nav-link" href="rediger.php">Critiques</a></li>
                
                <?php if(isset($_SESSION['user_id'])): ?>
                    <li class="nav-item"><span class="nav-link text-neon-green small">Salut, <?= $_SESSION['pseudo'] ?></span></li>
                    <li class="nav-item"><a class="nav-link btn-neon-red px-3" href="logout.php">Déconnexion</a></li>
                <?php else: ?>
                    <li class="nav-item"><a class="nav-link" href="connexion.php">Connexion</a></li>
                    <li class="nav-item"><a class="nav-link btn-neon-blue px-3" href="inscription.php">S'inscrire</a></li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>

    <div class="hero-section">
        <img src="assets/intro.gif" class="hero-video">
        <div class="hero-overlay">
            <div class="container h-100 d-flex align-items-center">
                <div class="col-lg-7 mt-5">
                    <span class="badge bg-danger mb-3 px-3 py-2 border-red text-white">EXCLUSIVITÉ 2026</span>
                    <h1 class="display-3 fw-bold text-white mb-3">L'AVENTURE <br><span class="text-neon-blue">COMMENCE ICI</span></h1>
                    <p class="lead text-white-50 mb-4" style="max-width: 600px;">Découvrez les derniers hits, rédigez vos propres critiques et rejoignez la plus grande communauté de gamers passionnés.</p>
                    <div class="d-flex gap-3">
                        <a href="tendances.php" class="btn btn-neon-blue px-5 py-3">VOIR LES HITS</a>
                        <a href="inscription.php" class="btn btn-outline-light px-5 py-3 border-2 fw-bold text-white text-decoration-none">S'INSCRIRE</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="container my-5 py-5">
        <div class="row text-center g-4 mb-5">
            <div class="col-md-4">
                <div class="glass-card h-100 border-blue p-4">
                    <i class="fas fa-gamepad fa-3x text-neon-blue mb-3"></i>
                    <h4 class="text-white fw-bold">EXPLOREZ</h4>
                    <p class="text-white-50 small">Accédez aux données de millions de jeux grâce à l'API RAWG en temps réel.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="glass-card h-100 border-red p-4">
                    <i class="fas fa-pen-nib fa-3x text-neon-red mb-3"></i>
                    <h4 class="text-white fw-bold">CRITIQUEZ</h4>
                    <p class="text-white-50 small">Notez vos jeux de 0 à 20 et partagez votre avis avec les membres.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="glass-card h-100 border-green p-4">
                    <i class="fas fa-users fa-3x text-neon-green mb-3"></i>
                    <h4 class="text-white fw-bold">COMMUNAUTÉ</h4>
                    <p class="text-white-50 small">Likez vos jeux favoris et suivez les tendances mondiales.</p>
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
            <p class="small">© 2026 Projet d'Étude. Données fournies par RAWG.io</p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="js/js.js"></script>
</body>
</html>