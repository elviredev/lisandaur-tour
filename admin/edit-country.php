<?php
require_once __DIR__ . '/../config/init.php';
require_once __DIR__ . '/../utils/admin-only.php';
require_once __DIR__ . '/../utils/token.php';
require_once __DIR__ . '/../utils/sanitize.php';
require_once __DIR__ . '/../utils/redirect-msg.php';

$page_title = "Modifier un pays";
include 'partials/header.php';

// Génération d'un token CSRF
$csrf_token = generateCSRFToken('csrf_token_edit_country');

// récupérer ID depuis l'URL
if (isset($_GET['id'])) {
  $id = sanitizeInt($_GET['id']);
  if (!$id) {
    // si ID invalide (ex. abc, -1, etc)
    redirectWithMessage(ROOT_URL . 'admin/manage-countries.php', 'edit-country', 'ID pays invalide 🤔');
  }

  // récupérer country depuis bdd
  $stmt = $connection->prepare("SELECT * FROM countries WHERE id = ?");
  $stmt->bind_param('i', $id);
  $stmt->execute();
  $result = $stmt->get_result();

  if ($result->num_rows === 1) {
    $country = $result->fetch_assoc();
  } else {
    // si ID est bien un entier mais inexistant
    redirectWithMessage(ROOT_URL . 'admin/manage-countries.php', 'edit-country', 'Pays non trouvé 🤔');
  }

  $stmt->close();
} else {
  // si ID non fournit dans l'URL
  redirectWithMessage(ROOT_URL . 'admin/manage-countries.php', 'edit-country', 'ID pays non fournit 🤔');
}
?>


<!-- FORM START -->
<section class="form__section form__section-add-country">
  <div class="container form__section-container dashboard__form-container">
    <h2>Modifier un pays</h2>

    <?php if (isset($_SESSION['edit-country'])): ?>
      <div class="alert__message error">
        <p>
          <?= $_SESSION['edit-country'];
          unset($_SESSION['edit-country']);
          ?>
        </p>
      </div>
    <?php endif; ?>

    <form action="<?= ROOT_URL ?>admin/edit-country-logic.php" enctype="multipart/form-data" method="POST">
      <input type="hidden" name="id" value="<?= e($country['id']) ?>">
      <input type="hidden" name="csrf_token_edit_country" value="<?= $csrf_token ?>">
      <input type="hidden" name="page" value="<?= e($_GET['page'] ?? 1) ?>">

      <input type="text" name="title" value="<?= e($country['title']) ?>" placeholder="Nom du pays">
      <textarea rows="4" name="description" placeholder="Description"><?= e($country['description']) ?></textarea>

      <div class="form__control">
        <label for="flag">Drapeau du pays</label>
        <?php if ($country['flag']): ?>
          <img
            src="<?= ROOT_URL . 'images/flags/' . e($country['flag']) ?>"
            alt="preview flag"
            class="previsualisation__img"
            id="preview-flag"
          >
        <?php endif; ?>
        <input type="file" name="flag" id="flag">
      </div>

      <div class="mt-1">
        <button type="submit" name="submit" class="btn btn-1">Modifier le pays</button>
      </div>
    </form>
  </div>
</section>
<!-- FORM END -->


<?php
include '../partials/footer.php'
?>