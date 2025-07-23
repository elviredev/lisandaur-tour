<?php
require_once __DIR__.'/../config/init.php';
require_once __DIR__ . '/../utils/admin-only.php';
require_once __DIR__.'/../utils/redirect-msg.php';
require_once __DIR__.'/../utils/validate-image.php';
require_once __DIR__.'/../utils/upload-file.php';
require_once __DIR__.'/../utils/upload-and-replace.php';
require_once __DIR__.'/../utils/sanitize.php';

// protection CSRF pour s'assurer que le form est bien soumis depuis mon site
if (!isset($_POST['csrf_token_edit_country']) || $_POST['csrf_token_edit_country'] !== $_SESSION['csrf_token_edit_country']) {
  redirectWithMessage(ROOT_URL . 'admin/manage-countries.php', 'edit-country', 'CSRF token invalide 🔐');
}

// nettoyage et validation des champs
$id = sanitizeInt($_POST['id'] ?? 0);
$title = sanitizeText($_POST['title'] ?? '');
$description = sanitizeText($_POST['description'] ?? '');
$flag = sanitizeFile($_FILES['flag'] ?? []);

// vérifier les champs requis
$errors = [];
if (!$id || !$title || !$description) $errors[] = 'Les champs "Nom du pays" et "Description" sont requis 😢';


// récupérer l'ancien flag si existant
$flag_to_save = null;
$flag_stmt = $connection->prepare('SELECT flag FROM countries WHERE id = ?');
$flag_stmt->bind_param('i', $id);
$flag_stmt->execute();
$flag_result = $flag_stmt->get_result();
$old_flag = $flag_result->fetch_assoc()['flag'] ?? null;
$flag_stmt->close();

// gestion du flag
if (!empty($_FILES['flag']['name'])) {
  // validation de l'image
  $imageError = validateImage($_FILES['flag'], 1_000_000);
  if ($imageError) {
    $errors[] = "Drapeau: " . $imageError;
  } else {
    // seulement si image valide on upload
    $flag_to_save = uploadAndReplace($_FILES['flag'], $old_flag, '../images/flags/');

    if (!$flag_to_save) {
      $errors[] = 'Impossible d\'enregistrer le drapeau 😢';
    }
  }
}

if(!empty($errors)) {
  $_SESSION['edit-country'] = implode('<br>', $errors);
  $_SESSION['edit-country-data'] = $_POST;
  header('location: ' . ROOT_URL . 'admin/edit-country.php?id=' . $id);
  exit;
}

// update country avec ou sans mise à jour du flag
if ($flag_to_save) {
  $stmt = $connection->prepare("UPDATE countries SET title = ?, description = ?, flag = ? WHERE id = ? LIMIT 1");
  $stmt->bind_param('sssi', $title, $description, $flag_to_save, $id);
} else {
  // update country sans flag
  $stmt = $connection->prepare("UPDATE countries SET title = ?, description = ? WHERE id = ? LIMIT 1");
  $stmt->bind_param('ssi', $title, $description, $id);
}

if ($stmt->execute()) {
  $_SESSION['edit-country-success'] = "Pays $title mis à jour avec succès 😊";
} else {
  $_SESSION['edit-country'] = "Echec pour la mise à jour de ce pays 😢";
}

$stmt->close();
unset($_SESSION['csrf_token_edit_country']);

// pagination: rester sur la même page et rediriger
$page = isset($_POST['page']) ? (int) $_POST['page'] : 1;
header('location: ' . ROOT_URL . 'admin/manage-countries.php?page=' .$page);
exit;