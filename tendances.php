<?php
session_start();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Revieweo | Tendances</title>
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

    <div class="container pt-5 mt-5">
        <h2 class="text-neon-blue mb-4 fw-bold pt-4 border-bottom border-secondary pb-2">TOP 20 DU MOIS</h2>
        <div class="row" id="game-list"></div>
    </div>

    <div class="modal fade" id="gameModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content glass-card border-blue text-white">
                <div class="modal-header border-0">
                    <h5 class="modal-title fw-bold text-neon-blue" id="modalTitle"></h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div id="modalTrailer" class="mb-3"></div>
                    <div class="row">
                        <div class="col-md-4">
                            <img id="modalImg" src="" class="img-fluid rounded mb-3">
                            <div class="badge bg-dark border border-info w-100 p-2 fs-6">Note : <span id="modalRating" class="text-neon-green"></span>/5</div>
                        </div>
                        <div class="col-md-8">
                            <h6 class="text-neon-green">L'AVIS DE LA COMMUNAUTÉ</h6>
                            <p id="modalDesc" class="text-white-50 small"></p>
                            <a href="rediger.html" class="btn btn-neon-blue w-100 mt-3 fw-bold">RÉDIGER MA CRITIQUE</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="js/js.js"></script>
</body>
</html>