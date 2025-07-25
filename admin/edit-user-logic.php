<?php
require_once __DIR__.'/../config/init.php';
require_once __DIR__.'/../utils/redirect-msg.php';
require_once __DIR__.'/../utils/validate-image.php';
require_once __DIR__.'/../utils/upload-file.php';
require_once __DIR__.'/../utils/upload-and-replace.php';
require_once __DIR__.'/../utils/sanitize.php';

// protection CSRF pour s'assurer que le form est bien soumis depuis mon site
if (!isset($_POST['csrf_token_edit_user']) || $_POST['csrf_token_edit_user'] !== $_SESSION['csrf_token_edit_user']) {
  redirectWithMessage(ROOT_URL . 'admin/manage-users.php', 'edit-user', 'CSRF token invalide 🔐');
}

// nettoyage et validation des champs
$id = sanitizeInt($_POST['id'] ?? 0);
$firstname = sanitizeText($_POST['firstname'] ?? '');
$lastname =sanitizeText($_POST['lastname'] ?? '');
$username = sanitizeText($_POST['username'] ?? '');
$is_admin = sanitizeInt($_POST['user_role'] ?? 0);

// vérifier les champs requis
$errors = [];
if (!$id || !$firstname || !$lastname || !$username) $errors[] = 'Les champs "Prénom", "Nom", "Pseudo" sont requis 😢';

// récupérer l'ancien avatar si existant
$avatar_to_save = null;
$avatar_stmt = $connection->prepare('SELECT avatar FROM users WHERE id = ?');
$avatar_stmt->bind_param('i', $id);
$avatar_stmt->execute();
$avatar_result = $avatar_stmt->get_result();
$old_avatar = $avatar_result->fetch_assoc()['avatar'] ?? null;
$avatar_stmt->close();

// gestion de l'avatar
if (!empty($_FILES['avatar']['name'])) {
  // validation de l'image
  $imageError = validateImage($_FILES['avatar'], 1_000_000);

  if ($imageError) {
    $errors[] = "Avatar: " . $imageError;
  } else {
    // seulement si image valide on upload
    $avatar_to_save = uploadAndReplace($_FILES['avatar'], $old_avatar, '../images/avatars/');

    if (!$avatar_to_save) {
      $errors[] = "Erreur lors de l'upload de l'avatar 😢";
    }
  }
}

if(!empty($errors)) {
  $_SESSION['edit-user'] = implode('<br>', $errors);
  $_SESSION['edit-user-data'] = $_POST;
  header('location: ' . ROOT_URL . 'admin/edit-user.php?id=' . $id);
  exit;
}

// update user avec ou sans mise à jour de l'avatar
if ($avatar_to_save) {
  $stmt = $connection->prepare("UPDATE users SET firstname = ?, lastname = ?, username = ?, is_admin = ?, avatar = ? WHERE id = ? LIMIT 1");
  $stmt->bind_param('sssisi', $firstname, $lastname, $username, $is_admin, $avatar_to_save, $id);
} else {
  // update user sans avatar
  $stmt = $connection->prepare("UPDATE users SET firstname = ?, lastname = ?, username = ?, is_admin = ? WHERE id = ? LIMIT 1");
  $stmt->bind_param('sssii', $firstname, $lastname, $username, $is_admin, $id);
}

if ($stmt->execute()) {
  $_SESSION['edit-user-success'] = "Utilisateur $firstname $lastname mis à jour avec succès 😊";
} else {
  $_SESSION['edit-user'] = "Echec pour la mise à jour de cet utilisateur 😢";
}

$stmt->close();
unset($_SESSION['csrf_token_edit_user']);

// pagination: rester sur la même page et rediriger
$page = isset($_POST['page']) ? (int) $_POST['page'] : 1;
header('location: ' . ROOT_URL . 'admin/manage-users.php?page=' .$page);
exit;