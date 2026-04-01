<?php
session_start();
?> 

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Revieweo | Connexion</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="css/style.css">
</head>
<body class="d-flex align-items-center justify-content-center vh-100 connexion-page">

    <div class="video-background">
        <img src="assets/intro.gif" class="bg-video">
        <div class="bg-overlay"></div>
    </div>

    <div class="glass-card border-blue col-11 col-sm-8 col-md-6 col-lg-4 position-relative p-4" style="z-index: 10;">
        <div class="mb-4">
            <a href="index.php" class="text-decoration-none text-neon-blue small fw-bold">
                <i class="fas fa-arrow-left me-1"></i>RETOUR
            </a>
        </div>

        <div class="text-center mb-4">
            <h2 class="text-neon-blue fw-bold h3">CONNEXION</h2>
            <p class="text-white-50 small">Ravi de vous revoir sur Revieweo Games</p>
        </div>

       <form action="login_action.php" method="POST">
    <div class="mb-3">
        <label class="form-label">ADRESSE EMAIL</label>
        <input type="email" name="email" class="form-control" required>
    </div>
    <div class="mb-4">
        <label class="form-label">MOT DE PASSE</label>
        <input type="password" name="password" class="form-control" required>
    </div>
    <button type="submit" class="btn btn-neon-blue w-100">SE CONNECTER</button>
</form>
    </div>

    <script>
        // Fonction pour basculer la visibilité du mot de passe
        function togglePass(id, icon) {
            const input = document.getElementById(id);
            input.type = input.type === "password" ? "text" : "password";
            icon.classList.toggle('fa-eye');
            icon.classList.toggle('fa-eye-slash');
        }
    </script>
</body>
</html>