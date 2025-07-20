<?php
require_once __DIR__ . '/../config/init.php';
require_once __DIR__ . '/../utils/admin-only.php';
require_once __DIR__ . '/../utils/token.php';
require_once __DIR__ . '/../utils/sanitize.php';

$page_title = "Ajouter un utilisateur";
include 'partials/header.php';

// générer un token CSRF
$csrf_token = generateCsrfToken('csrf_token_add_user');

// préremplir les champs du form en cas d'erreur
$firstname = $_SESSION['add-user-data']['firstname'] ?? '';
$lastname = $_SESSION['add-user-data']['lastname'] ?? '';
$username = $_SESSION['add-user-data']['username'] ?? '';
$email = $_SESSION['add-user-data']['email'] ?? '';
$selected_role = isset($_SESSION['add-user-data']['user_role'])
      ? (string)$_SESSION['add-user-data']['user_role']
      : '0';

// delete session data
unset($_SESSION['add-user-data']);
?>


<!-- FORM START -->
<section class="form__section form__section-add-user">
  <div class="container form__section-mt form__section-mb form__section-container dashboard__form-container">
    <h2>Ajouter un utilisateur</h2>
    <?php if (isset($_SESSION['add-user'])): ?>
      <div class="alert__message error">
        <?= $_SESSION['add-user'];
        unset($_SESSION['add-user']);
        ?>
      </div>
    <?php endif; ?>

    <form action="<?= ROOT_URL ?>admin/add-user-logic.php" enctype="multipart/form-data" method="POST">
      <input type="hidden" name="csrf_token_add_user" value="<?= $csrf_token; ?>">

      <input type="text" name="firstname" value="<?= e($firstname ?? '') ?>" placeholder="Prénom">
      <input type="text" name="lastname" value="<?= e($lastname ?? '') ?>" placeholder="Nom">
      <input type="text" name="username" value="<?= e($username ?? '') ?>" placeholder="Pseudo">
      <input type="email" name="email" value="<?= e($email ?? '') ?>" placeholder="Email">
      <input type="password" name="create_password" placeholder="Créer mot de passe">
      <input type="password" name="confirm_password" placeholder="Confirmer mot de passe">

      <div class="form__control">
        <label for="role">Rôle Utilisateur</label>
        <select id="role" name="user_role">
          <option value="0" <?= $selected_role == '0' ? 'selected' : '' ?>>Auteur</option>
          <option value="1" <?= $selected_role == '1' ? 'selected' : '' ?>>Admin</option>
        </select>
      </div>

      <div class="form__control">
        <label for="avatar">Avatar Utilisateur</label>
        <img src="#" alt="preview avatar" class="previsualisation__img" id="preview-avatar" style="display: none;">
        <input type="file" name="avatar" id="avatar">
      </div>

      <div class="mt-1">
        <button type="submit" name="submit" class="btn btn-1">Ajouter l'utilisateur</button>
      </div>

    </form>
  </div>
</section>
<!-- FORM END -->


<?php
include '../partials/footer.php'
?>