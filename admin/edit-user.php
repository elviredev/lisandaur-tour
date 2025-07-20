<?php
require_once __DIR__ . '/../config/init.php';
require_once __DIR__ . '/../utils/admin-only.php';
require_once __DIR__.'/../utils/token.php';
require_once __DIR__.'/../utils/redirect-msg.php';
require_once __DIR__.'/../utils/sanitize.php';

$page_title = "Modifier un utilisateur";
include 'partials/header.php';

// Génération d'un token CSRF
$csrf_token = generateCSRFToken('csrf_token_edit_user');

// récupérer ID depuis l'URL
if (isset($_GET['id'])) {
  $id = sanitizeInt($_GET['id']);

  if (!$id) {
    // si ID invalide (ex. abc, -1, etc)
    redirectWithMessage(ROOT_URL . 'admin/manage-users.php', 'edit-user', 'ID utilisateur invalide 🤔');
  }

  // récupérer user depuis bdd
  $stmt = $connection->prepare("SELECT * FROM users WHERE id = ?");
  $stmt->bind_param('i', $id);
  $stmt->execute();
  $result = $stmt->get_result();

  if ($result->num_rows === 1) {
    $user = $result->fetch_assoc();
  } else {
    // si ID est bien un entier mais inexistant
    redirectWithMessage(ROOT_URL . 'admin/manage-users.php', 'edit-user', 'Utilisateur non trouvé 🤔');
  }

  $stmt->close();

} else {
  // si ID non fournit dans l'URL
  redirectWithMessage(ROOT_URL . 'admin/manage-users.php', 'edit-user', 'ID utilisateur non fournit 🤔');
}

?>


<!-- FORM START -->
<section class="form__section form__section-add-user">
  <div class="container form__section-container dashboard__form-container">
    <h2>Modifier un utilisateur</h2>

    <?php if (isset($_SESSION['edit-user'])): ?>
      <div class="alert__message error">
        <p>
          <?= $_SESSION['edit-user'];
          unset($_SESSION['edit-user']);
          ?>
        </p>
      </div>
    <?php endif; ?>

    <form action="<?= ROOT_URL ?>admin/edit-user-logic.php" enctype="multipart/form-data" method="POST">
      <input type="hidden" name="id" value="<?= e($user['id']) ?>">
      <input type="hidden" name="csrf_token_edit_user" value="<?= $csrf_token ?>">
      <input type="hidden" name="page" value="<?= $_GET['page'] ?? 1 ?>">

      <input type="text" name="firstname" value="<?= e($user['firstname']) ?>" placeholder="Prénom">
      <input type="text" name="lastname" value="<?= e($user['lastname']) ?>" placeholder="Nom">
      <input type="text" name="username" value="<?= e($user['username']) ?>" placeholder="Pseudo">

      <div class="form__control">
        <label for="role">Rôle Utilisateur</label>
        <select id="role" name="user_role">
          <option value="0" <?= $user['is_admin'] == 0 ? 'selected' : '' ?>>Auteur</option>
          <option value="1" <?= $user['is_admin'] == 1 ? 'selected' : '' ?>>Admin</option>
        </select>
      </div>

      <div class="form__control">
        <label for="avatar">Avatar Utilisateur</label>
        <?php if ($user['avatar']): ?>
          <img src="<?= ROOT_URL . 'images/avatars/' . e($user['avatar'])  ?>" alt="preview avatar" class="previsualisation__img" id="preview-avatar">
        <?php endif; ?>
        <input type="file" name="avatar" id="avatar">
      </div>

      <div class="mt-1">
        <button type="submit" name="submit" class="btn btn-1">Modifier l'utilisateur</button>
      </div>

    </form>
  </div>
</section>
<!-- FORM END -->


<?php
include '../partials/footer.php'
?>