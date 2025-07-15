<!doctype html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta
      name="viewport"
      content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <!--  CUSTOM CSS  -->
  <link rel="stylesheet" href="css/style.css">
  <!-- ICONSCOUT  -->
  <link rel="stylesheet" href="https://unicons.iconscout.com/release/v4.0.8/css/line.css">
  <title>LSA-Tour</title>
</head>
<body>

<section class="form__section">
  <div class="container form__section-container">
    <div class="logo">
      <a href="index.php">
        <span>LSA</span>
        <img src="images/plane.svg" alt="">
        <span>TOUR</span>
      </a>
    </div>

    <h2>Inscrivez-vous ici</h2>

    <div class="alert__message error">
      <p>Ici un message d'erreur sera affiché</p>
    </div>

    <form action="#" enctype="multipart/form-data">
      <input type="text" placeholder="Votre prénom">
      <input type="text" placeholder="Votre nom">
      <input type="text" placeholder="Votre pseudo">
      <input type="email" placeholder="Votre email">
      <input type="password" placeholder="Créer votre mot de passe">
      <input type="password" placeholder="Confirmer votre mot de passe">
      <div class="form__control">
        <label for="avatar">Votre avatar</label>
        <input type="file" id="avatar">
      </div>

      <button type="submit" class="btn-form">S'inscrire</button>
      <small>Vous avez déja un compte? <a href="signin.php">Se connecter</a></small>
    </form>
  </div>
</section>

</body>
</html>