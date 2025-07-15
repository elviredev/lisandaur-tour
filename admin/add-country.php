<?php
$page_title = "Ajouter un pays";
include 'partials/header.php'
?>


<!-- FORM START -->
<section class="form__section form__section-add-country">
  <div class="container form__section-container dashboard__form-container">
    <h2>Ajouter un pays</h2>

    <div class="alert__message error">
      <p>Ici un message d'erreurs sera affiché</p>
    </div>

    <form action="" enctype="multipart/form-data">

      <input type="text" name="title" placeholder="Nom du pays">
      <textarea rows="4" name="body" placeholder="Description"></textarea>

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