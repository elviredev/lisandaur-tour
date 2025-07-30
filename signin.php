<?php
require_once __DIR__ . '/config/init.php';
require_once __DIR__ . '/utils/sanitize.php';
require_once __DIR__ . '/utils/token.php';

// générer un token CSRF au chargement de la page
$csrf_token = generateCSRFToken('csrf_token_signin');

// récupérer les données du form en cas d'erreur d'enregistrement
$username_email = $_SESSION['signin-data']['username_email'] ?? '';
unset($_SESSION['signin-data']);
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
  <title>Se connecter | LSA-Tour</title>
</head>
<body>

<section class="form__section">
  <div class="container form__section-container">
    <div class="logo">
      <a href="<?= ROOT_URL ?>">
        <span>LSA</span>
        <img src="<?= ROOT_URL ?>images/plane.svg" alt="">
        <span>TOUR</span>
      </a>
    </div>

    <h2>Se connecter ici</h2>

    <?php if(isset($_SESSION['signup-success'])): ?>
      <div class="alert__message success">
        <p>
          <?= $_SESSION['signup-success'];
          unset($_SESSION['signup-success']);
          ?>
        </p>
      </div>
    <?php elseif(isset($_SESSION['signin'])): ?>
      <div class="alert__message error">
        <p>
          <?= $_SESSION['signin'];
          unset($_SESSION['signin']);
          ?>
        </p>
      </div>
    <?php endif; ?>

    <form action="<?= ROOT_URL ?>signin-logic.php" method="POST">
      <input type="hidden" name="csrf_token_signin" value="<?= $csrf_token ?>">

      <input type="text" name="username_email" value="<?= e($username_email) ?>" placeholder="Votre email ou pseudo">
      <input type="password" name="password" placeholder="Votre mot de passe">

      <button type="submit" name="submit" class="btn-form">Se connecter</button>
      <small>Vous n'avez pas encore de compte? <a href="<?= ROOT_URL ?>signup.php">S'inscrire</a></small>
    </form>
  </div>
</section>

<script>
  const successMessage = document.querySelector('.alert__message.success');
  if (successMessage) {
    setTimeout(() => {
      successMessage.classList.add('hide');
    }, 3000);
  }
</script>
</body>
</html>