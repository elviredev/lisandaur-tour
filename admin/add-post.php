<?php
require_once __DIR__ . '/../config/init.php';
require_once __DIR__ . '/../utils/sanitize.php';
require_once __DIR__ . '/../utils/token.php';

$page_title = "Ajouter un article";
include 'partials/header.php';

// Génération d'un token CSRF
$csrf_token = generateCSRFToken('csrf_token_add_post');

// fetch countries from bdd
$stmt = $connection->prepare("SELECT * FROM countries ORDER BY title");
$stmt->execute();
$countries = $stmt->get_result();
$stmt->close();

// récupérer les anciennes valeurs si erreur
$title = $_SESSION['add-post-data']['title'] ?? '';
$country_id = $_SESSION['add-post-data']['country'] ?? '';
$year = $_SESSION['add-post-data']['year'] ?? '';
$body = $_SESSION['add-post-data']['body'] ?? '';
$is_featured = isset($_SESSION['add-post-data']['is_featured']) ? 'checked' : '';

// nettoie une fois utilisé
unset($_SESSION['add-post-data']);
?>


<!-- FORM START -->
<section class="form__section form__section-mb form__section-mt">
  <div class="container form__section-container dashboard__form-container">
    <h2>Ajouter un article</h2>

    <?php if (isset($_SESSION['add-post'])): ?>
      <div class="alert__message error">
        <p>
          <?= $_SESSION['add-post'];
          unset($_SESSION['add-post']);
          ?>
        </p>
      </div>
    <?php endif; ?>

    <form action="<?= ROOT_URL ?>admin/add-post-logic.php" enctype="multipart/form-data" method="POST">
      <input type="hidden" name="csrf_token_add_post" value="<?= $csrf_token ?>">

      <input type="text" name="title" value="<?= e($title) ?>" placeholder="Titre de l'article">

      <select name="country">
        <?php foreach ($countries as $country): ?>
          <option value="<?= $country['id'] ?>"><?= e($country['title']) ?></option>
        <?php endforeach; ?>
      </select>

      <select name="year">
        <?php
          $currentYear = date('Y');
          for ($y = $currentYear; $y >= 2010; $y--) {
            $selected = ($year == $y) ? 'selected' : '';
            echo "<option value='$y' $selected>$y</option>";
          }
        ?>
      </select>

      <textarea rows="10" name="body" placeholder="Contenu de l'article"><?= e($body) ?></textarea>

      <?php if (isset($_SESSION['user_is_admin'])): ?>
        <div class="form__control inline">
          <input type="checkbox" id="is_featured" value="1" name="is_featured" <?= $is_featured ?> />
          <label for="is_featured">En vedette</label>
        </div>
      <?php endif; ?>

      <div class="form__control">
        <label for="thumbnail">Ajouter une image principale</label>
        <img src="#" alt="preview thumbnail" class="previsualisation__img" id="preview-thumbnail" style="display: none;">
        <input type="file" name="thumbnail" id="thumbnail">
      </div>

      <div class="form__control-row ">
        <div class="form__control">
          <label for="image_1">Ajouter une autre image (facultatif)</label>
          <img src="" alt="preview image 1" class="previsualisation__img" id="preview-image_1" style="display: none;">
          <input type="file" name="image_1" id="image_1">
        </div>

        <div class="form__control">
          <label for="image_1">Ajouter une autre image (facultatif)</label>
          <img src="" alt="preview image 2" class="previsualisation__img" id="preview-image_2" style="display: none;">
          <input type="file" name="image_2" id="image_2">
        </div>
      </div>

      <div class="mt-1">
        <button type="submit" name="submit" class="btn btn-1">Ajouter l'article</button>
      </div>

    </form>
  </div>
</section>
<!-- FORM END -->


<?php
include '../partials/footer.php'
?>