<?php
session_start();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Revieweo | Critiques</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark fixed-top">
        <div class="container">
            <a class="navbar-brand fw-bold" href="index.php">REVIEWEO <span class="text-neon-green">GAMES</span></a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto gap-3 align-items-center">
                    <li class="nav-item"><a class="nav-link" href="index.php">Accueil</a></li>
                    <li class="nav-item"><a class="nav-link" href="tendances.php">Tendances</a></li>
                    <li class="nav-item"><a class="nav-link active" href="rediger.php">Critiques</a></li>
                    
                    <?php if(isset($_SESSION['user_id'])): ?>
                        <li class="nav-item"><span class="nav-link text-neon-green small">ID: <?= $_SESSION['pseudo'] ?></span></li>
                        <li class="nav-item"><a class="nav-link btn-neon-red px-3" href="logout.php">Déconnexion</a></li>
                    <?php else: ?>
                        <li class="nav-item"><a class="nav-link" href="connexion.php">Connexion</a></li>
                        <li class="nav-item"><a class="nav-link btn-neon-blue px-3" href="inscription.php">S'inscrire</a></li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container mt-5 pt-5">
        <div class="row">
            <div class="col-lg-8">
                <div class="glass-card border-blue p-4 mb-4">
                    <h3 class="text-white fw-bold mb-4 text-uppercase">Rechercher un jeu à <span class="text-neon-blue">critiquer</span></h3>
                    <div class="input-group mb-3">
                        <input type="text" id="searchCritique" class="form-control bg-dark text-white border-secondary" placeholder="Nom du jeu (ex: Elden Ring)...">
                        <button class="btn btn-neon-blue" onclick="searchGamesCritique(1)">RECHERCHER</button>
                    </div>
                </div>

                <div id="critique-results" class="row"></div>
                
                <div class="d-flex justify-content-center gap-3 mt-4 mb-5">
                    <button class="btn btn-outline-info" onclick="searchGamesCritique(currentPage - 1)">Précédent</button>
                    <span id="pageDisplay" class="text-white align-self-center">Page 1</span>
                    <button class="btn btn-outline-info" onclick="searchGamesCritique(currentPage + 1)">Suivant</button>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="glass-card border-green p-4 sticky-top" style="top: 100px;">
                    <h4 class="text-neon-green fw-bold mb-4 text-uppercase">Rédiger ma critique</h4>
                    
                    <?php if(isset($_SESSION['user_id'])): ?>
                        <form action="critique_action.php" method="POST">
                            <div class="mb-3">
                                <label class="form-label text-white-50 small fw-bold">NOM DU JEU</label>
                                <input type="text" name="titre" id="selectedGameTitle" class="form-control" placeholder="Sélectionnez un jeu à gauche" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label text-white-50 small fw-bold">NOTE (0 à 20)</label>
                                <input type="number" name="note" min="0" max="20" class="form-control" placeholder="15" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label text-white-50 small fw-bold">MON AVIS</label>
                                <textarea name="contenu" class="form-control" rows="6" placeholder="Partagez votre expérience..." required></textarea>
                            </div>
                            <button type="submit" class="btn btn-neon-green w-100 fw-bold py-2">PUBLIER SUR REVIEWEO</button>
                        </form>
                    <?php else: ?>
                        <div class="text-center py-4">
                            <i class="fas fa-lock fa-3x text-white-50 mb-3"></i>
                            <p class="text-white">Vous devez être connecté pour rédiger une critique.</p>
                            <a href="connexion.php" class="btn btn-neon-blue btn-sm">Se connecter</a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="gameModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content glass-card border-blue text-white">
                <div class="modal-header border-0">
                    <h5 class="modal-title fw-bold text-neon-blue" id="modalTitle"></h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div id="modalTrailer" class="mb-3 text-center"></div>
                    <div class="row">
                        <div class="col-md-4 text-center">
                            <img id="modalImg" src="" class="img-fluid rounded border border-secondary mb-3">
                            <div class="badge bg-dark border border-info w-100 p-2 fs-6">Note API : <span id="modalRating" class="text-neon-green"></span>/5</div>
                        </div>
                        <div class="col-md-8">
                            <h6 class="text-neon-green fw-bold text-uppercase">Résumé</h6>
                            <p id="modalDesc" class="text-white small" style="text-align: justify;"></p>
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