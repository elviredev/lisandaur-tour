<?php
require_once __DIR__. '/../config/init.php';
require_once __DIR__. '/../utils/admin-only.php';
require_once __DIR__. '/../utils/redirect-msg.php';
require_once __DIR__. '/../utils/sanitize.php';

// protection: methode POST uniquement
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['id'], $_POST['csrf_token_delete_user'])) {
  // Vérification du token CSRF
  if (!hash_equals($_SESSION['csrf_token_delete_user'], $_POST['csrf_token_delete_user'])) {
    redirectWithMessage(ROOT_URL . 'admin/manage-users.php', 'delete-user', 'CSRF token invalide 🔐');
  }

  $id = sanitizeInt($_POST['id']);

  if(!$id) {
    redirectWithMessage(ROOT_URL . 'admin/manage-users.php', 'delete-user', 'ID utilisateur incorrect ❌');
  }

  // récupérer user en bdd
  $stmt = $connection->prepare("SELECT firstname, lastname, avatar FROM users WHERE id = ?");
  $stmt->bind_param("i", $id);
  $stmt->execute();
  $result = $stmt->get_result();

  // vérifier que le user existe
  if ($result->num_rows !== 1) {
    redirectWithMessage(ROOT_URL . 'admin/manage-users.php', 'delete-user', 'Cet utilisateur n\'existe pas ❌');
  }

  $user = $result->fetch_assoc();
  $stmt->close();

  // suppression de l'avatar s'il existe
  if(!empty($user['avatar'])) {
    $avatar_path = '../images/avatars/' . basename($user['avatar']);
    if (file_exists($avatar_path)) {
      unlink($avatar_path);
    }
  }

  // récupérer all images (thumbnail, image_1, image_2) des posts du user et les supprimer dans le dossier /images/posts
  // TODO

  // supprimer user
  $stmt = $connection->prepare("DELETE FROM users WHERE id = ?");
  $stmt->bind_param("i", $id);
  $stmt->execute();

  // résultat de la suppression
  if ($stmt->affected_rows > 0) {
    $_SESSION['delete-user-success'] = "{$user['firstname']} {$user['lastname']} supprimé avec succès 😊";
  } else {
    $_SESSION['delete-user'] = "{$user['firstname']} {$user['lastname']} ne peut être supprimé 😢";
  }

  $stmt->close();
} else {
  // requête non autorisée
  redirectWithMessage(ROOT_URL . 'admin/manage-users.php', 'delete-user', 'Cette requête n\'est pas autorisée 🚫');
}

// pagination : rester sur la page courante
$page = isset($_POST['page']) ? (int) $_POST['page'] : 1;

header('location: ' . ROOT_URL . 'admin/manage-users.php?page=' .$page);
exit;