<?php
require_once __DIR__ . '/vendor/autoload.php';
session_start();
header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false]); exit();
}

$db = (new App\Config\Database())->getConnection();
$id_critique = $_POST['id'];
$id_user = $_SESSION['user_id'];

// On vérifie si l'utilisateur a déjà liké cette critique
$check = $db->prepare("SELECT id FROM `like` WHERE id_critique = ? AND id_user = ?");
$check->execute([$id_critique, $id_user]);

// Si le like existe déjà, on le supprime, sinon on l'ajoute
if ($check->fetch()) {
    $db->prepare("DELETE FROM `like` WHERE id_critique = ? AND id_user = ?")->execute([$id_critique, $id_user]);
    $liked = false;
} else {
    $db->prepare("INSERT INTO `like` (id_critique, id_user) VALUES (?, ?)")->execute([$id_critique, $id_user]);
    $liked = true;
}
// On compte le nombre total de likes pour cette critique
$count = $db->prepare("SELECT COUNT(*) FROM `like` WHERE id_critique = ?");
$count->execute([$id_critique]);

// On renvoie une réponse JSON avec le statut du like et le nombre total de likes
echo json_encode(['success' => true, 'liked' => $liked, 'total' => $count->fetchColumn()]);