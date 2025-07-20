<?php
require_once __DIR__ . '/../config/init.php';
require_once __DIR__ . '/../utils/admin-only.php';
require_once __DIR__.'/../utils/validate-image.php';
require_once __DIR__.'/../utils/upload-file.php';
require_once __DIR__ . '/../utils/redirect-msg.php';

// Protection CSRF pour s'assurer que le form est bien soumis depuis mon site
if (!isset($_POST['csrf_token_add_user']) || $_POST['csrf_token_add_user'] !== $_SESSION['csrf_token_add_user']) {
  redirectWithMessage(ROOT_URL . 'admin/manage-users.php', 'add-user', 'CSRF token invalide 🔐');
}

// get form data if signup button was clicked
if (isset($_POST['submit'])) {
  // sanityze inputs
  $firstname = trim($_POST['firstname']);
  $lastname = trim($_POST['lastname']);
  $username = trim($_POST['username']);
  $email = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL);
  $create_password = trim($_POST['create_password']);
  $confirm_password = trim($_POST['confirm_password']);
  $is_admin = filter_var($_POST['user_role'], FILTER_SANITIZE_NUMBER_INT);
  $avatar = $_FILES['avatar'];

  // stocker les erreurs
  $errors = [];

  // validate des champs
  if (!$firstname || !$lastname || !$username || !$email) $errors[] = "Ces champs sont requis";
  if (strlen($create_password) < 8 || strlen($confirm_password) < 8) $errors[] = "Le mot de passe doit avoir 8+ caractères";
  if ($create_password !== $confirm_password) $errors[] = "Les mots de passe ne correspondent pas";
  if (!$avatar['name']) $errors[] = "Veuillez télecharger un avatar";

  // validation de l'image si elle est présente
  if ($avatar['name']) {
    $imageError = validateImage($_FILES['avatar'], 1_000_000);
    if ($imageError) $errors[] = $imageError;
  }

  // si aucune erreur, poursuivre
  if (empty($errors)) {
    // hash password
    $hashed_password = password_hash($create_password, PASSWORD_DEFAULT);

    // check if username or email already exist in bdd
    $query = $connection->prepare("SELECT id FROM users WHERE username = ? OR email = ? LIMIT 1");
    $query->bind_param("ss", $username, $email);
    $query->execute();
    $result = $query->get_result();

    if ($result->num_rows > 0) {
      $_SESSION['add-user'] = "Pseudo ou Email déja utilisé 😢";
    } else {
      // upload image
      $avatar_name = uploadFile($_FILES['avatar'], '../images/avatars/');

      if (!$avatar_name) {
        $_SESSION['add-user'] = "Erreur lors de l'upload de l'avatar 😢";
      } else {
        // enregistrer l'utilisateur dans la bdd
        $insert = $connection->prepare("INSERT INTO users (firstname, lastname, username, email, password, avatar, is_admin) VALUES (?, ?, ?, ?, ?, ?, ?) ");
        $insert->bind_param("ssssssi", $firstname, $lastname, $username, $email, $hashed_password, $avatar_name, $is_admin);
        $insert->execute();

        // suppression de l'image dans le dossier en cas d'echec d'insertion
        if ($insert->affected_rows === 0 && file_exists('../images/avatars/' . $avatar_name)) {
          unlink('../images/avatars/' . $avatar_name);
        }

        if ($insert->affected_rows > 0) {
          $query->close();
          redirectWithMessage(ROOT_URL . 'admin/manage-users.php','add-user-success', "Utilisateur $firstname $lastname ajouté avec succès 😊!");
        } else {
          redirectWithMessage(ROOT_URL . 'admin/add-user.php', 'add-user', 'Erreur Base de données. Veuillez réessayer 😢');
        }
      }
    }
    // pour invalider le token après usage (optionnel)
    unset($_SESSION['csrf_token_add_user']);

  } else {
    // stocker les erreurs dans la session
    $_SESSION['add-user'] = implode("<br>", $errors);

    // stocker les champs du form dans la session
    $_SESSION['add-user-data'] = [
      "firstname" => $firstname,
      "lastname" => $lastname,
      "username" => $username,
      "email" => $email,
      "user_role" => $is_admin,
    ];
  }

  // redirection vers le formulaire
  header('location: ' . ROOT_URL . 'admin/add-user.php');
  exit;

} else {
  // if button wasn't clicked, bounce back to add-user page
  header('location: ' . ROOT_URL . 'admin/add-user.php');
  exit;
}