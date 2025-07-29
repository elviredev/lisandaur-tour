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

      <!-- Toolbar start -->
      <div>
        <div id="toolbar">
          <button type="button" onclick="execCmd('bold')"><b>B</b></button>
          <button type="button" onclick="execCmd('italic')"><i>I</i></button>
          <button type="button" onclick="execCmd('underline')"><u>U</u></button>
          <button type="button" onclick="execCmd('formatBlock', '<h1>')">H1</button>
          <button type="button" onclick="execCmd('formatBlock', '<h2>')">H2</button>
          <button type="button" onclick="execCmd('formatBlock', '<p>')">P</button>
          <button type="button" onclick="execCmd('insertUnorderedList')">
            <i class=" uil-list-ui-alt"></i>
          </button>

          <button type="button" onclick="execCmd('createLink')">
            <i class="uil-link-alt"></i>
          </button>

          <button type="button" onclick="execCmd('removeFormat')">
            <i class="uil-trash-alt"></i>
          </button>
          <div class="toolbar__dropdown">
            <button type="button" class="toolbar__dropdown-dropdown-toggle" title="Alignement">
              <i class="uil-list-ul"></i>
            </button>
            <div class="toolbar__dropdown-dropdown-menu">
              <button type="button" onclick="execCmd('justifyLeft')" title="Align left">
                <i class="uil uil-align-left"></i>
              </button>
              <button type="button" onclick="execCmd('justifyCenter')" title="Align center">
                <i class="uil uil-align-center"></i>
              </button>
              <button type="button" onclick="execCmd('justifyRight')" title="Align right">
                <i class="uil uil-align-right"></i>
              </button>
              <button type="button" onclick="execCmd('justifyFull')" title="Justify">
                <i class="uil uil-align-justify"></i>
              </button>
            </div>
          </div>
        </div>

        <!-- Zone éditable -->
        <div id="editor" contenteditable="true" >
          <?= $body ?>
        </div>

        <!-- Champ caché à envoyer -->
        <textarea name="body" id="hiddenBody" style="display:none;"></textarea>
      </div>
      <!-- Toolbar end -->

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

  <script>
    function execCmd(command, value = null) {
      // Pour la création d'un lien dans l'editor
      if (command === 'createLink') {
        const selection = window.getSelection();
        let existingUrl = '';

        // 🔍 Vérifie si on est dans un lien existant
        if (selection.rangeCount > 0) {
          const parentEl = selection.anchorNode?.parentElement;
          if (parentEl && parentEl.tagName === 'A') {
            existingUrl = parentEl.getAttribute('href') || '';
          }
        }

        // 📝 Demande une URL avec l'URL actuelle préremplie si présente
        let url = prompt('Entrez l’URL :', existingUrl);

        if (url) {
          // Ajoute https:// si manquant
          if (!url.startsWith('http://') && !url.startsWith('https://')) {
            url = 'https://' + url;
          }

          document.execCommand(command, false, url);

          // Applique target="_blank" et rel sur le lien nouvellement créé
          if (selection.rangeCount > 0) {
            const anchor = selection.anchorNode?.parentElement;
            if (anchor && anchor.tagName === 'A') {
              anchor.setAttribute('target', '_blank');
              anchor.setAttribute('rel', 'noopener noreferrer');
            }
          }
        }
      } else {
        document.execCommand(command, false, value);
      }
    }

    document.querySelector('form').addEventListener('submit', function () {
      document.getElementById('hiddenBody').value = document.getElementById('editor').innerHTML;
    });
  </script>

<?php
include '../partials/footer.php'
?>