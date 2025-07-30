<?php
require_once __DIR__ . '/../config/init.php';
require_once __DIR__.'/../utils/validate-image.php';
require_once __DIR__.'/../utils/upload-file.php';
require_once __DIR__ . '/../utils/redirect-msg.php';
require_once __DIR__ . '/../utils/sanitize.php';

// Protection CSRF pour s'assurer que le form est bien soumis depuis mon site
if (!isset($_POST['csrf_token_add_post']) || $_POST['csrf_token_add_post'] !== $_SESSION['csrf_token_add_post']) {
  redirectWithMessage(ROOT_URL . 'admin/', 'add-post', 'CSRF token invalide 🔐');
}

if (isset($_POST['submit'])) {
  // sanityze inputs
  $author_id = $_SESSION['user-id'] ?? null;
  $title = sanitizeText($_POST['title'] ?? '');
  $year = sanitizeText($_POST['year'] ?? '');
  $body = trim($_POST['body'] ?? '');
  $country_id = sanitizeInt($_POST['country'] ?? 0);
  $is_featured = isset($_POST['is_featured']) ? 1 : 0;

  $thumbnail = sanitizeFile($_FILES['thumbnail'] ?? []);
  $image_1 = sanitizeFile($_FILES['image_1'] ?? []);
  $image_2 = sanitizeFile($_FILES['image_2'] ?? []);

  // stocker les erreurs
  $errors = [];

  // validation des champs
  if (empty($author_id)) $errors[] = "Utilisateur non identifié.";
  if (empty($title)) $errors[] = "Entrer le titre de l'article.";
  if (empty($year)) $errors[] = "Entrer une année pour l'article.";
  if (empty($body)) $errors[] = "Entrer le contenu de l'article.";
  if (empty($country_id)) $errors[] = "Sélectionner un pays pour l'article.";
  if (empty($thumbnail['name'])) $errors[] = "Choisir une image pour l'article.";

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

  // si aucune erreur, poursuivre
  if (empty($errors)) {
    // upload images
    $thumbnail_name = uploadFile($thumbnail, '../images/posts/');
    $image_1_name = $image_1 && $image_1['name'] ? uploadFile($image_1, '../images/posts/') : null;
    $image_2_name = $image_2 && $image_2['name'] ? uploadFile($image_2, '../images/posts/') : null;

    if (!$thumbnail_name) {
      $_SESSION['add-post'] = "Erreur lors de l'upload de la vignette 😢";
    } else {
      // Si featured, mettre les autres à 0
      if ($is_featured === 1) {
        mysqli_query($connection, "UPDATE posts SET is_featured = 0");
      }

      // insertion en bdd
      $insert = $connection->prepare("
        INSERT INTO posts (title, body, year, thumbnail, image_1, image_2, country_id, author_id, is_featured) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)
      ");

      $insert->bind_param("ssssssiii", $title, $body, $year, $thumbnail_name, $image_1_name, $image_2_name, $country_id, $author_id, $is_featured);
      $insert->execute();

      if ($insert->affected_rows > 0) {
        $insert->close();
        redirectWithMessage(ROOT_URL . 'admin/','add-post-success', "Nouvel article ajouté avec succès 😊!");
      } else {
        redirectWithMessage(ROOT_URL . 'admin/add-post.php', 'add-post', 'Erreur Base de données. Veuillez réessayer 😢');
      }
    }
    // pour invalider le token après usage (optionnel)
    unset($_SESSION['csrf_token_add_post']);
  } else {
    // stocker les erreurs dans la session
    $_SESSION['add-post'] = implode("<br>", $errors);

    // stocker les champs du form dans la session
    $_SESSION['add-post-data'] = $_POST;
  }

  // redirection vers le formulaire
  header('location: ' . ROOT_URL . 'admin/add-post.php');
  exit;
} else {
  header('location: ' . ROOT_URL . 'admin/add-post.php');
  exit;
}