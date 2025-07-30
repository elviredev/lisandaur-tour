<?php
require_once __DIR__ . '/config/init.php';
require_once __DIR__ . '/utils/redirect-msg.php';
require_once __DIR__ . '/utils/sanitize.php';
require_once __DIR__.'/utils/validate-image.php';
require_once __DIR__.'/utils/upload-file.php';

// Protection CSRF pour s'assurer que le form est bien soumis depuis mon site
if (!isset($_POST['csrf_token_signup']) || $_POST['csrf_token_signup'] !== $_SESSION['csrf_token_signup']) {
  redirectWithMessage(ROOT_URL . 'signup.php', 'signup', 'CSRF token invalide 🔐');
}

// get signup form data when signup button was clicked
if (isset($_POST["submit"])) {
  // sanityze inputs
  $firstname = sanitizeText($_POST["firstname"] ?? '');
  $lastname = sanitizeText($_POST["lastname"] ?? '');
  $username = sanitizeText($_POST["username"] ?? '');
  $email = sanitizeEmail($_POST["email"] ?? '');
  $create_password = sanitizeText($_POST["create_password"] ?? '');
  $confirm_password = sanitizeText($_POST["confirm_password"] ?? '');
  $avatar = sanitizeFile($_FILES["avatar"] ?? []);

  // stocker les erreurs
  $errors = [];

  // validate des champs
  if (!$firstname) $errors[] = "Veuillez entrer votre prénom";
  if (!$lastname) $errors[] = "Veuillez entrer votre nom";
  if (!$username) $errors[] = "Veuillez entrer votre pseudo";
  if (!$email) $errors[] = "Veuillez entrer une adresse email correcte";
  if (strlen($create_password) < 8 || strlen($confirm_password) < 8) $errors[] = "Le mot de passe doit avoir 8+ caractères";
  if ($create_password !== $confirm_password) $errors[] = "Les mots de passe ne correspondent pas";
  if (!$avatar['name']) $errors[] = "Veuillez télecharger votre avatar";

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
    $query = $connection->prepare("SELECT * FROM users WHERE username = ? OR email = ? LIMIT 1");
    $query->bind_param("ss", $username, $email);
    $query->execute();
    $result = $query->get_result();

    if ($result->num_rows > 0) {
      $_SESSION['signup'] = "Pseudo ou Email déja utilisé 😢";
    } else {
      // upload image
      $avatar_name = uploadFile($_FILES['avatar'], './images/avatars/');

      if (!$avatar_name) {
        $_SESSION['signup'] = "Erreur lors de l'upload de l'avatar 😢";
      } else {
        // enregistrer l'utilisateur dans la bdd
        $insert = $connection->prepare("INSERT INTO users (firstname, lastname, username, email, password, avatar) VALUES (?, ?, ?, ?, ?, ?) ");
        $insert->bind_param("ssssss", $firstname, $lastname, $username, $email, $hashed_password, $avatar_name);
        $insert->execute();

        $_SESSION['signup-success'] = "Inscription réussie 🎉 Connectez-vous.";
        header('location: ' . ROOT_URL . 'signin.php');
        exit;
      }
    }
    // régénérer le token après chaque soumission réussie pour éviter la réutilisation (optionnel)
    unset($_SESSION['csrf_token_signup']);

    $query->close();
  } else {
    // stocker les erreurs dans la session
    $_SESSION['signup'] = implode("<br>", $errors);

    // stocker les champs du form dans la session
    $_SESSION['signup-data'] = [
      "firstname" => $firstname,
      "lastname" => $lastname,
      "username" => $username,
      "email" => $email
    ];
  }

  // redirection vers le formulaire
  header('location: ' . ROOT_URL . 'signup.php');
  exit;

} else {
  // if button wasn't clicked, bounce to signup page
  header('location: ' . ROOT_URL . 'signup.php');
  exit;
}
