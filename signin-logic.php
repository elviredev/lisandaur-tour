<?php
require_once __DIR__ . '/config/init.php';

// get signin form data when signin button was clicked
if (isset($_POST["submit"])) {
  // sanityze inputs
  $username_email = filter_var($_POST['username_email'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
  $password = filter_var($_POST['password'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);

  // stocker les erreurs
  $errors = [];

  // validation des champs
  if (!$username_email) $errors[] = "Pseudo ou Email requis";
  if (!$password) $errors[] = "Mot de passe requis";

  // si aucune erreur, poursuivre
  if (empty($errors)) {
    // fetch user from bdd
    $query = $connection->prepare("SELECT * FROM users WHERE username = ? OR email = ?");
    $query->bind_param("ss", $username_email, $username_email);
    $query->execute();
    $result = $query->get_result();

    if ($result->num_rows == 1) {
      // convert record into associated array
      $user_record = $result->fetch_assoc();
      $db_password = $user_record["password"];

      // compare form password with db password
      if (password_verify($password, $db_password)) {
        // set session for access control
        $_SESSION['user-id'] = $user_record["id"];

        // set session if user is an admin
        if ($user_record['is_admin'] == 1) {
          $_SESSION['user_is_admin'] = true;
        }

        $query->close();

        // log user in
        header('location: ' . ROOT_URL . 'admin/');
        exit;
      } else {
        $_SESSION['signin'] = "Merci de vérifier votre mot de passe et de réessayer";
      }
    } else {
      $_SESSION['signin'] = "Cet utilisateur n'existe pas";
    }
  } else {
    // stocker les erreurs dans la session
    $_SESSION['signin'] = implode("<br>", $errors);

    // stocker les champs du form dans la session
    $_SESSION['signin-data'] = [
      "username_email" => $username_email
    ];
  }

  // redirection vers le formulaire
  header('location: ' . ROOT_URL . 'signin.php');
  exit;

} else {
  // if button wasn't clicked, bounce to signin page
  header('location: ' . ROOT_URL . 'signin.php');
  exit;
}
