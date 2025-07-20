<?php
require_once __DIR__ . '/../config/init.php';
require_once __DIR__ . '/../utils/admin-only.php';
require_once __DIR__.'/../utils/validate-image.php';
require_once __DIR__.'/../utils/upload-file.php';
require_once __DIR__ . '/../utils/redirect-msg.php';
require_once __DIR__ . '/../utils/sanitize.php';

// Protection CSRF pour s'assurer que le form est bien soumis depuis mon site
if (!isset($_POST['csrf_token_add_country']) || $_POST['csrf_token_add_country'] !== $_SESSION['csrf_token_add_country']) {
  redirectWithMessage(ROOT_URL . 'admin/manage-countries.php', 'add-country', 'CSRF token invalide 🔐');
}

if (isset($_POST['submit'])) {
  // sanityze inputs
  $title = sanitizeText($_POST['title'] ?? '');
  $description = sanitizeText($_POST['description'] ?? '');
  $flag = sanitizeFile($_FILES['flag'] ?? []);

  // stocker les erreurs
  $errors = [];

  // validation des champs
  if (!$title || !$description) $errors[] = 'Les champs "Nom" et "Description" sont requis';
  if (!$flag['name']) $errors[] = "Veuillez télécharger un drapeau";

  // validation de l'image si elle est présente
  if ($flag['name']) {
    $imageError = validateImage($_FILES['flag'], 1_000_000);
    if ($imageError) $errors[] = $imageError;
  }

  // si aucune erreur, poursuivre
  if (empty($errors)) {
    // upload image
    $flag_name = uploadFile($_FILES['flag'], '../images/flags/');

    if (!$flag_name) {
      $_SESSION['add-country'] = "Erreur lors de l'upload du drapeau 😢";
    } else {
      // enregistrer le drapeau dans la bdd
      $insert = $connection->prepare("INSERT INTO countries (title, description, flag) VALUES (?, ?, ?)");
      $insert->bind_param("sss", $title, $description, $flag_name);
      $insert->execute();

      // suppression de l'image dans le dossier en cas d'echec d'insertion
      if ($insert->affected_rows === 0 && file_exists('../images/flags/' . $flag_name)) {
        unlink('../images/flags/' . $flag_name);
      }

      if ($insert->affected_rows > 0) {
        $insert->close();
        redirectWithMessage(ROOT_URL . 'admin/manage-countries.php','add-country-success', "Pays $title ajouté avec succès 😊!");
      } else {
        redirectWithMessage(ROOT_URL . 'admin/add-country.php', 'add-country', 'Erreur Base de données. Veuillez réessayer 😢');
      }
    }
    // pour invalider le token après usage (optionnel)
    unset($_SESSION['csrf_token_add_country']);
  } else {
    // stocker les erreurs dans la session
    $_SESSION['add-country'] = implode("<br>", $errors);

    // stocker les champs du form dans la session
    $_SESSION['add-country-data'] = [
      "title" => $title,
      "description" => $description,
    ];
  }

  // redirection vers le formulaire
  header('location: ' . ROOT_URL . 'admin/add-country.php');
  exit;

} else {
  header('location: ' . ROOT_URL . 'admin/add-country.php');
  exit;
}