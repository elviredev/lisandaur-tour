<?php
$page_title = "Modifier un article";
include 'partials/header.php'
?>


<!-- FORM START -->
<section class="form__section form__section-edit-post form__section-mb form__section-mt">
  <div class="container form__section-container dashboard__form-container">
    <h2>Modifier un article</h2>

    <div class="alert__message error">
      <p>Ici un message d'erreurs sera affiché</p>
    </div>

    <form action="" enctype="multipart/form-data">

      <input type="text" name="title" placeholder="Titre de l'article">

      <select name="country">
        <option value="1">Japon</option>
        <option value="1">Islande</option>
        <option value="1">Corée</option>
        <option value="1">Espagne</option>
        <option value="1">Italie</option>
        <option value="1">Angleterre</option>
        <option value="1">Madeire</option>
      </select>

      <select name="year">
        <option value="1">2025</option>
        <option value="1">2024</option>
        <option value="1">2023</option>
        <option value="1">2022</option>
        <option value="1">2021</option>
        <option value="1">2020</option>
        <option value="1">2019</option>
        <option value="1">2018</option>
        <option value="1">2017</option>
      </select>

      <textarea rows="10" name="body" placeholder="Contenu de l'article"></textarea>

      <div class="form__control inline">
        <input type="checkbox" id="is_featured" checked />
        <label for="is_featured">En vedette</label>
      </div>

      <!-- THUMBNAIL -->
      <div class="form__control">
        <label for="thumbnail">Changer l'image principale</label>
        <img src="#" alt="preview thumbnail" class="previsualisation__img" id="preview-thumbnail" style="display: none;">
        <input type="file" name="thumbnail" id="thumbnail">
      </div>
      <!-- IMAGES -->
      <div class="form__control-row ">
        <div class="form__control">
          <label for="image_1">Changer l'image (facultatif)</label>
          <div class="remove-image">
            <img src="" alt="preview image 1" class="previsualisation__img" id="preview-image_1" style="display: none;">
            <button type="button" class="btn sm danger remove-image-btn" data-image-type="image_1">Supprimer</button>
          </div>

          <input type="hidden" name="remove_image_1" id="remove-image_1" value="0">
          <input type="file" name="image_1" id="image_1">
        </div>

        <div class="form__control">
          <label for="image_1">Changer l'image (facultatif)</label>
          <div class="remove-image">
            <img src="" alt="preview image 2" class="previsualisation__img" id="preview-image_2" style="display: none;">
            <button type="button" class="btn sm danger remove-image-btn" data-image-type="image_1">Supprimer</button>
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