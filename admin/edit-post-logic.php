<?php
require_once __DIR__.'/../config/init.php';
require_once __DIR__.'/../utils/redirect-msg.php';
require_once __DIR__.'/../utils/validate-image.php';
require_once __DIR__.'/../utils/upload-file.php';
require_once __DIR__.'/../utils/upload-and-replace.php';
require_once __DIR__.'/../utils/sanitize.php';

// Protection CSRF pour s'assurer que le form est bien soumis depuis mon site
if (!isset($_POST['csrf_token_edit_post']) || $_POST['csrf_token_edit_post'] !== $_SESSION['csrf_token_edit_post']) {
  redirectWithMessage(ROOT_URL . 'admin/', 'edit-post', 'CSRF token invalide 🔐');
}

// Vérifie si formulaire soumis
if (!isset($_POST['submit'])) {
  redirectWithMessage(ROOT_URL . 'admin/', 'edit-post', 'Formulaire non soumis ❌');
}

// Récupération des champs
$post_id = sanitizeInt($_POST['id'] ?? null);
$title = sanitizeText($_POST['title'] ?? null);
$body = trim($_POST['body'] ?? null);
$country_id = sanitizeInt($_POST['country'] ?? null);
$year = sanitizeText($_POST['year'] ?? null);
$is_featured = isset($_POST['is_featured']) ? 1 : 0;

$thumbnail = sanitizeFile($_FILES['thumbnail'] ?? []);
$image_1 = sanitizeFile($_FILES['image_1'] ?? []);
$image_2 = sanitizeFile($_FILES['image_2'] ?? []);

// Redirection si ID invalide
if (!$post_id) {
  redirectWithMessage(ROOT_URL . 'admin/', 'edit-post', 'ID de l\'article invalide ❌');
}

// Récupérer le post original
$query = "SELECT * FROM posts WHERE id = ?";
$stmt = $connection->prepare($query);
$stmt->bind_param('i', $post_id);
$stmt->execute();
$result = $stmt->get_result();
$existing_post = $result->fetch_assoc();

if (!$existing_post) {
  redirectWithMessage(ROOT_URL . 'admin/', 'edit-post', 'Article non trouvé ❌');
}

// Check droits d'accès
$current_user_id = $_SESSION['user-id'] ?? null;
$is_admin = $_SESSION['user_is_admin'] ?? false;

if ($existing_post['author_id'] != $current_user_id && !$is_admin) {
  redirectWithMessage(ROOT_URL . 'admin/', 'edit-post', 'Accès interdit 🚫');
}

// Validation
$errors = [];
if (empty($title)) $errors[] = "Entrer le titre de l'article.";
if (empty($year)) $errors[] = "Entrer une année pour l'article.";
if (empty($body)) $errors[] = "Entrer le contenu de l'article.";
if (empty($country_id)) $errors[] = "Sélectionner un pays pour l'article.";

// validation de l'image si elle est présente
if ($thumbnail && $thumbnail['name']) {
  $imageError = validateImage($_FILES['thumbnail']);
  if ($imageError) $errors[] = "Vignette: " . $imageError;
}

if ($image_1 && $image_1['name']) {
  $imageError = validateImage($_FILES['image_1']);
  if ($imageError) $errors[] = "Image 1: " . $imageError;
}

if ($image_2 && $image_2['name']) {
  $imageError = validateImage($_FILES['image_2']);
  if ($imageError) $errors[] = "Image 2: " . $imageError;
}

if(!empty($errors)) {
  $_SESSION['edit-post'] = implode('<br>', $errors);
  $_SESSION['edit-post-data'] = $_POST;
  header('location: ' . ROOT_URL . 'admin/edit-post.php?id=' . $post_id);
  exit;
}

// Gérer la suppression manuelle des images existantes
foreach (['image_1', 'image_2'] as $field) {
  $remove_field = 'remove_' . $field;
  if (isset($_POST[$remove_field]) && $_POST[$remove_field] == '1') {
    $old_file = $existing_post[$field];
    if ($old_file && file_exists('../images/posts/' . $old_file)) {
      unlink('../images/posts/' . $old_file);
    }
    // Vide le champ pour le champ à mettre à jour ensuite
    $existing_post[$field] = null;
  }
}

// Uploads images
$new_thumbnail = uploadAndReplace($thumbnail, $existing_post['thumbnail'], '../images/posts/');
$new_image_1 = uploadAndReplace($image_1, $existing_post['image_1'], '../images/posts/');
$new_image_2 = uploadAndReplace($image_2, $existing_post['image_2'], '../images/posts/');

// Forcer à NULL en BDD si image supprimée sans remplacement
if (isset($_POST['remove_image_1']) && $_POST['remove_image_1'] == '1' && empty($image_1['name'])) {
  $new_image_1 = null;
}
if (isset($_POST['remove_image_2']) && $_POST['remove_image_2'] == '1' && empty($image_2['name'])) {
  $new_image_2 = null;
}

// Mettre à jour le champ is_featured
if ($is_featured === 1) {
  mysqli_query($connection, "UPDATE posts SET is_featured = 0");
}

// Update post en bdd
$stmt = $connection->prepare("
    UPDATE posts 
    SET title = ?, body = ?, country_id = ?, year = ?, is_featured = ?, thumbnail = ?, image_1 = ?, image_2 = ?
    WHERE id = ?
");
$stmt->bind_param("ssisisssi", $title, $body, $country_id, $year, $is_featured, $new_thumbnail, $new_image_1, $new_image_2, $post_id);

$success = $stmt->execute();
$stmt->close();

unset($_SESSION['csrf_token_edit_post']);

if ($success) {
  $page = $_POST['page'] ?? 1;
  redirectWithMessage(ROOT_URL . 'admin/index.php?page=' .$page, 'edit-post-success', 'Article modifié avec succès 😊');
} else {
  redirectWithMessage(ROOT_URL . 'admin/edit-post.php?id=' . $post_id, 'edit-post', "Erreur lors de la mise à jour de l'article ❌");
}



