<?php
require_once __DIR__ . '/config/init.php';

// récupérer les données du form en cas d'erreur d'enregistrement
$firstname = $_SESSION['signup-data']['firstname'] ?? '';
$lastname = $_SESSION['signup-data']['lastname'] ?? '';
$username = $_SESSION['signup-data']['username'] ?? '';
$email = $_SESSION['signup-data']['email'] ?? '';
$create_password = $_SESSION['signup-data']['create_password'] ?? '';
$confirm_password = $_SESSION['signup-data']['confirm_password'] ?? '';
// delete signup data session
unset($_SESSION['signup-data']);
?>

<!doctype html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta
      name="viewport"
      content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <!--  CUSTOM CSS  -->
  <link rel="stylesheet" href="<?= ROOT_URL ?>css/style.css">
  <!-- ICONSCOUT  -->
  <link rel="stylesheet" href="https://unicons.iconscout.com/release/v4.0.8/css/line.css">
  <title>S'inscrire | LSA-Tour</title>
</head>
<body>

<section class="form__section form__section-signup">
  <div class="container form__section-container">
    <div class="logo">
      <a href="<?= ROOT_URL ?>">
        <span>LSA</span>
        <img src="<?= ROOT_URL ?>images/plane.svg" alt="">
        <span>TOUR</span>
      </a>
    </div>

    <h2>Inscrivez-vous ici</h2>

    <?php if (isset($_SESSION['signup'])): ?>
      <div class="alert__message error">
        <p>
          <?= $_SESSION['signup'];
          unset($_SESSION['signup']);
          ?>
        </p>
      </div>
    <?php endif; ?>

    <form action="<?= ROOT_URL ?>signup-logic.php" enctype="multipart/form-data" method="POST">
      <input type="text" name="firstname" value="<?= htmlspecialchars($firstname) ?>" placeholder="Votre prénom">
      <input type="text" name="lastname" value="<?= htmlspecialchars($lastname) ?>" placeholder="Votre nom">
      <input type="text" name="username" value="<?= htmlspecialchars($username) ?>" placeholder="Votre pseudo">
      <input type="email" name="email" value="<?= htmlspecialchars($email) ?>" placeholder="Votre email">
      <input type="password" name="create_password" placeholder="Créer votre mot de passe">
      <input type="password" name="confirm_password" placeholder="Confirmer votre mot de passe">
      <div class="form__control">
        <label for="avatar">Votre avatar</label>
        <input type="file" name="avatar" id="avatar">
      </div>

      <button type="submit" name="submit" class="btn-form">S'inscrire</button>
      <small>Vous avez déja un compte? <a href="signin.php">Se connecter</a></small>
    </form>
  </div>
</section>

</body>
</html>