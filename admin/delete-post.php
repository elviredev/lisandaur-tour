<?php
require_once __DIR__. '/../config/init.php';
require_once __DIR__. '/../utils/redirect-msg.php';
require_once __DIR__. '/../utils/sanitize.php';
require_once __DIR__. '/../utils/pagination-utils.php';

// protection: methode POST uniquement
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['id'], $_POST['csrf_token_delete_post'])) {
  // Vérification du token CSRF
  if (!hash_equals($_SESSION['csrf_token_delete_post'], $_POST['csrf_token_delete_post'])) {
    redirectWithMessage(ROOT_URL . 'admin/', 'delete-post', 'CSRF token invalide 🔐');
  }

  $id = sanitizeInt($_POST['id']);

  if(!$id) {
    redirectWithMessage(ROOT_URL . 'admin/', 'delete-post', 'ID de l\'article incorrect ❌');
  }

  // récupérer le post de la bdd afin de supprimer  thumbnail, image 1, image 2 du dossier images/posts/
  $stmt = $connection->prepare("SELECT * FROM posts WHERE id = ?");
  $stmt->bind_param("i", $id);
  $stmt->execute();
  $result = $stmt->get_result();

  // vérifier que le post existe
  if ($result->num_rows !== 1) {
    redirectWithMessage(ROOT_URL . 'admin/', 'delete-post', 'Article non trouvé ❌');
  }

  $post = $result->fetch_assoc();
  $stmt->close();

  // Check droits d'accès
  $current_user_id = $_SESSION['user-id'] ?? null;
  $is_admin = $_SESSION['user_is_admin'] ?? false;

  if ($post['author_id'] != $current_user_id && !$is_admin) {
    redirectWithMessage(ROOT_URL . 'admin/', 'delete-post', 'Accès interdit 🚫');
  }

  // suppression thumbnail, image_1, image_2 s'ils existent
  if(!empty($post['thumbnail'])) {
    $thumbnail_path = '../images/posts/' . basename($post['thumbnail']);
    if (file_exists($thumbnail_path)) {
      unlink($thumbnail_path);
    }
  }
  if(!empty($post['image_1'])) {
    $image_1_path = '../images/posts/' . basename($post['image_1']);
    if (file_exists($image_1_path)) {
      unlink($image_1_path);
    }
  }
  if(!empty($post['image_2'])) {
    $image_2_path = '../images/posts/' . basename($post['image_2']);
    if (file_exists($image_2_path)) {
      unlink($image_2_path);
    }
  }

  // supprimer le post
  $stmt = $connection->prepare("DELETE FROM posts WHERE id = ?");
  $stmt->bind_param("i", $id);
  $stmt->execute();

  // résultat de la suppression
  if ($stmt->affected_rows > 0) {
    $_SESSION['delete-post-success'] = "{$post['title']} supprimé avec succès 😊";
  } else {
    $_SESSION['delete-post'] = "{$post['title']} ne peut être supprimé 😢";
  }

  $stmt->close();
  unset($_SESSION['csrf_token_delete_post']);

} else {
  // requête non autorisée
  redirectWithMessage(ROOT_URL . 'admin/', 'delete-post', 'Cette requête n\'est pas autorisée 🚫');
}

// pagination : rester sur la page courante ou revenir à la page précédente
$page = isset($_POST['page']) ? (int) $_POST['page'] : 1;
$is_admin = $_SESSION['user_is_admin'] ?? false;
$current_user_id = $_SESSION['user-id'] ?? null;

// si admin, on compte tous les posts sinon on compte les posts du user courant
$where = $is_admin ? '' : "author_id = $current_user_id";
$total_pages = getTotalPages($connection, 'posts', $where, 4);

// redirection logique
$page = min($page, $total_pages);
header('location: ' . ROOT_URL . 'admin/index.php?page=' .$page);
exit;