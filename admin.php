<?php
require_once __DIR__ . '/vendor/autoload.php';
session_start();

// Sécurité : seul l'admin (role 2) accède à cette page
if (!isset($_SESSION['role']) || $_SESSION['role'] < 2) {
    header("Location: index.php");
    exit();
}

$db = (new App\Config\Database())->getConnection();

// 1. Récupérer tous les utilisateurs
$users = $db->query("SELECT * FROM user ORDER BY id DESC")->fetchAll(PDO::FETCH_ASSOC);

// 2. Récupérer toutes les critiques (Triées par épinglage puis par date)
$sql = "SELECT c.*, u.pseudo 
        FROM critique c 
        LEFT JOIN user u ON c.id_user = u.id 
        ORDER BY c.pinned DESC, c.date_creation DESC";

$critiques = $db->query($sql)->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Revieweo | Administration</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="css/style.css">
</head>
<body class="bg-dark text-white">
    <div class="container py-5">
        <h1 class="fw-bold mb-5 text-center text-uppercase">Console <span class="text-neon-red">Admin</span></h1>

        <div class="row g-4">
            <div class="col-lg-4">
                <div class="glass-card border-blue p-4 h-100">
                    <h4 class="text-neon-blue mb-4"><i class="fas fa-users"></i> Utilisateurs</h4>
                    <div class="table-responsive">
                        <table class="table table-dark table-sm align-middle">
                            <thead>
                                <tr>
                                    <th>Pseudo</th>
                                    <th>Rôle</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($users as $u): ?> 
                                <tr>
                                    <td class="small"><?= htmlspecialchars($u['pseudo']) ?></td> 
                                    <td>
                                        <form action="admin_action.php" method="POST" class="d-flex gap-1">
                                            <input type="hidden" name="user_id" value="<?= $u['id'] ?>"> 
                                            <input type="hidden" name="action" value="update_role"> 
                                            <select name="new_role" class="form-select form-select-sm bg-dark text-white border-secondary" onchange="this.form.submit()"> 
                                                <option value="0" <?= $u['role'] == 0 ? 'selected' : '' ?>>0 (User)</option>
                                                <option value="1" <?= $u['role'] == 1 ? 'selected' : '' ?>>1 (Critique)</option>
                                                <option value="2" <?= $u['role'] == 2 ? 'selected' : '' ?>>2 (Admin)</option>
                                            </select>
                                        </form>
                                    </td>
                                    <td>
                                        <a href="admin_action.php?delete_user=<?= $u['id'] ?>" class="text-danger" onclick="return confirm('Supprimer cet utilisateur ?')">
                                            <i class="fas fa-user-slash"></i>
                                        </a>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="col-lg-8">
                <div class="glass-card border-red p-4 h-100">
                    <h4 class="text-neon-red mb-4"><i class="fas fa-comments"></i> Modération Intégrale</h4>
                    <div class="table-responsive">
                        <table class="table table-dark align-middle">
                            <thead>
                                <tr>
                                    <th>Jeu</th>
                                    <th>Auteur</th>
                                    <th>Note</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($critiques as $c): ?> 
                                <tr class="<?= $c['pinned'] ? 'border-start border-4 border-warning bg-opacity-10 bg-warning' : '' ?>"> 
                                    <td>
                                        <?php if($c['pinned']): ?><i class="fas fa-thumbtack text-warning me-1"></i><?php endif; ?>
                                        <span class="fw-bold"><?= htmlspecialchars($c['titre']) ?></span>
                                    </td>
                                    <td class="small text-white-50">@<?= htmlspecialchars($c['pseudo'] ?? 'Anonyme') ?></td>
                                    <td><span class="badge bg-dark border border-info text-neon-blue"><?= $c['note'] ?>/20</span></td>
                                    <td>
                                        <div class="d-flex gap-2">
                                            <a href="admin_action.php?pin_critique=<?= $c['id'] ?>" 
                                               class="btn btn-sm <?= $c['pinned'] ? 'btn-warning' : 'btn-outline-warning' ?>" 
                                               title="Épingler en avant">
                                                <i class="fas fa-thumbtack"></i>
                                            </a>
                                            <a href="admin_action.php?delete_critique=<?= $c['id'] ?>" 
                                               class="btn btn-sm btn-outline-danger" 
                                               onclick="return confirm('Supprimer cet avis ?')">
                                                <i class="fas fa-trash"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="text-center mt-5">
            <a href="index.php" class="btn btn-outline-light"><i class="fas fa-arrow-left me-2"></i>Retour au site</a>
        </div>
    </div>
</body>
</html>