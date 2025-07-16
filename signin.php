<?php
require_once __DIR__ . '/config/init.php';
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
    <?php endif; ?>
    <form action="#" enctype="multipart/form-data">
      <input type="email" placeholder="Votre email ou pseudo">
      <input type="password" placeholder="Votre mot de passe">

      <button type="submit" class="btn-form">Se connecter</button>
      <small>Vous n'avez pas encore de compte? <a href="signup.php">S'inscrire</a></small>
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