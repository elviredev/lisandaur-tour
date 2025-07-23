<?php
require_once __DIR__ . '/../config/init.php';
require_once __DIR__ . '/../utils/token.php';
require_once __DIR__ . '/../utils/sanitize.php';
require_once __DIR__ . '/../utils/redirect-msg.php';

$page_title = "Modifier un article";
include 'partials/header.php';

// Génération d'un token CSRF
$csrf_token = generateCSRFToken('csrf_token_edit_post');

// récupérer l'ID du post depuis l'URL
$post_id = sanitizeInt($_GET['id'] ?? null);

// si aucun ID redirection
if(!$post_id) {
  redirectWithMessage(ROOT_URL . 'admin/', 'edit-post', "L'ID de l'article est manquant ❌");
}

// récupérer le infos du post
$query = "SELECT * FROM posts WHERE id = ?";
$stmt = $connection->prepare($query);
$stmt->bind_param('i', $post_id);
$stmt->execute();
$result = $stmt->get_result();
$post = $result->fetch_assoc();

// si post introuvable, redirection
if(!$post) {
  redirectWithMessage(ROOT_URL . 'admin/', 'edit-post', 'Article introuvable ❌');
}

// Vérification des droits : auteur OU admin
$current_user_id = $_SESSION['user_id'] ?? null;
$is_admin = $_SESSION['user_is_admin'] ?? false;

if ($post['author_id'] != $current_user_id && !$is_admin) {
  redirectWithMessage(ROOT_URL . 'admin/', 'edit-post', 'Accès non autorisé ❌');
}

// récupérer les pays
$stmt = $connection->prepare("SELECT * FROM countries ORDER BY title");
$stmt->execute();
$countries = $stmt->get_result();

// récupérer les anciennes valeurs si erreur
$title = $_SESSION['edit-post-data']['title'] ?? '';
$country_id = $_SESSION['edit-post-data']['country'] ?? '';
$year = $_SESSION['edit-post-data']['year'] ?? '';
$body = $_SESSION['edit-post-data']['body'] ?? '';
$is_featured = isset($_SESSION['edit-post-data']['is_featured']) ? 'checked' : '';

// nettoie une fois utilisé
unset($_SESSION['edit-post-data']);
?>


<!-- FORM START -->
<section class="form__section form__section-edit-post form__section-mb form__section-mt">
  <div class="container form__section-container dashboard__form-container">
    <h2>Modifier un article</h2>

    <?php if (isset($_SESSION['edit-post'])): ?>
      <div class="alert__message error">
        <p>
          <?= $_SESSION['edit-post'];
          unset($_SESSION['edit-post']);
          ?>
        </p>
      </div>
    <?php endif; ?>

    <form action="<?= ROOT_URL ?>admin/edit-post-logic.php" enctype="multipart/form-data" method="POST">
      <input type="hidden" name="id" value="<?= e($post['id']) ?>">
      <input type="hidden" name="csrf_token_edit_post" value="<?= $csrf_token ?>">
      <input type="hidden" name="page" value="<?= e($_GET['page'] ?? 1) ?>">

      <input type="text" name="title" value="<?= e($title ?: $post['title']) ?>" placeholder="Titre de l'article">

      <?php
        $selected_country_id = $country_id !== '' ? (int)$country_id : (int)$post['country_id'];
      ?>
      <select name="country">
        <?php foreach ($countries as $country): ?>
          <option value="<?= e($country['id']) ?>"
            <?= (int)$country['id'] == $selected_country_id ? "selected" : "" ?>
          >
            <?= e($country['title']) ?>
          </option>
        <?php endforeach; ?>
      </select>

      <select name="year">
        <?php
        $currentYear = date('Y');
        $selected_year = $year ?: $post['year'];
        for ($y = $currentYear; $y >= 2010; $y--) {
          $selected = ($selected_year == $y) ? 'selected' : '';
          echo "<option value='$y' $selected>$y</option>";
        }
        ?>
      </select>

      <textarea rows="10" name="body" placeholder="Contenu de l'article"><?= e($body ?: $post['body']) ?></textarea>

      <?php if (isset($_SESSION['user_is_admin'])): ?>
        <div class="form__control inline">
          <input
              type="checkbox"
              name="is_featured"
              id="is_featured"
              value="1"
            <?= ($is_featured || $post['is_featured']) ? "checked" : "" ?>
          >
          <label for="is_featured">En vedette</label>
        </div>
      <?php endif; ?>

      <!-- THUMBNAIL -->
      <div class="form__control">
        <label for="thumbnail">Changer l'image principale</label>
        <?php if ($post['thumbnail']): ?>
          <img src="<?= ROOT_URL . 'images/posts/' . $post['thumbnail'] ?>" alt="preview thumbnail" class="previsualisation__img" id="preview-thumbnail">
        <?php endif; ?>
        <input type="file" name="thumbnail" id="thumbnail">
      </div>

      <!-- IMAGES -->
      <div class="form__control-row ">
        <div class="form__control">
          <label for="image_1">Changer l'image (facultatif)</label>
          <div class="remove-image">
            <img
                src="<?= $post['image_1'] ? ROOT_URL . 'images/posts/' . $post['image_1'] : '' ?>"
                alt="preview image 1"
                class="previsualisation__img"
                id="preview-image_1"
                style="<?= $post['image_1'] ? '' : 'display: none;' ?>"
            >
            <?php if (!empty($post['image_1'])): ?>
              <button type="button" class="btn sm danger remove-image-btn" data-image-type="image_1">Supprimer</button>
            <?php endif; ?>
          </div>

          <input type="hidden" name="remove_image_1" id="remove-image_1" value="0">
          <input type="file" name="image_1" id="image_1">
        </div>

        <div class="form__control">
          <label for="image_2">Changer l'image (facultatif)</label>
          <div class="remove-image">
            <img
                src="<?= $post['image_2'] ? ROOT_URL . 'images/posts/' . $post['image_2'] : '' ?>"
                alt="preview image 2"
                class="previsualisation__img"
                id="preview-image_2"
                style="<?= $post['image_2'] ? '' : 'display: none;' ?>"
            >
            <?php if (!empty($post['image_2'])): ?>
              <button type="button" class="btn sm danger remove-image-btn" data-image-type="image_2">Supprimer</button>
            <?php endif; ?>
          </div>

          <input type="hidden" name="remove_image_2" id="remove-image_2" value="0">
          <input type="file" name="image_2" id="image_2">
        </div>
      </div>

      <div class="mt-1">
        <button type="submit" name="submit" class="btn btn-1">Modifier l'article</button>
      </div>

    </form>
  </div>
</section>
<!-- FORM END -->


<?php
include '../partials/footer.php'
?>