<?php
require_once __DIR__ . '/../config/init.php';
require_once __DIR__ . '/../utils/admin-only.php';
require_once __DIR__ . '/../utils/sanitize.php';
require_once __DIR__ . '/../utils/token.php';

$page_title = "Ajouter un pays";
include 'partials/header.php';

// générer un token CSRF
$csrf_token = generateCsrfToken('csrf_token_add_country');

// Pré-remplissage des champs en cas d’erreur de validation
$title = $_SESSION['add-country-data']['title'] ?? '';
$description = $_SESSION['add-country-data']['description'] ?? '';

unset($_SESSION['add-country-data']);
?>


<!-- FORM START -->
<section class="form__section form__section-add-country">
  <div class="container form__section-container dashboard__form-container">
    <h2>Ajouter un pays</h2>

    <?php if(isset($_SESSION['add-country'])): ?>
      <div class="alert__message error">
        <p>
          <?= $_SESSION['add-country'];
          unset($_SESSION['add-country']);
          ?>
        </p>
      </div>
    <?php endif; ?>

    <form action="<?= ROOT_URL ?>admin/add-country-logic.php" enctype="multipart/form-data" method="POST">
      <input type="hidden" name="csrf_token_add_country" value="<?= $csrf_token ?>">
      <input type="text" name="title" value="<?= e($title) ?>" placeholder="Nom du pays">
      <textarea rows="4" name="description" placeholder="Description"><?= e($description) ?></textarea>

      <div class="form__control">
        <label for="flag">Drapeau du pays</label>
        <img src="#" alt="preview flag" class="previsualisation__img" id="preview-flag" style="display: none;">
        <input type="file" name="flag" id="flag">
      </div>

      <div class="mt-1">
        <button type="submit" name="submit" class="btn btn-1">Ajouter le pays</button>
      </div>

    </form>
  </div>
</section>
<!-- FORM END -->


<?php
include '../partials/footer.php'
?>