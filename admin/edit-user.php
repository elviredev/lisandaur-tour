<?php
$page_title = "Modifier un utilisateur";
include 'partials/header.php'
?>


<!-- FORM START -->
<section class="form__section form__section-add-user">
  <div class="container form__section-container dashboard__form-container">
    <h2>Modifier un utilisateur</h2>

    <div class="alert__message error">
      <p>Ici un message d'erreurs sera affiché</p>
    </div>

    <form action="" enctype="multipart/form-data">

      <input type="text" name="firstname" placeholder="Prénom">
      <input type="text" name="lastname" placeholder="Nom">

      <div class="form__control">
        <label for="role">Rôle Utilisateur</label>
        <select id="role" name="user_role">
          <option value="1">Auteur</option>
          <option value="1">Admin</option>
        </select>
      </div>

      <div class="form__control">
        <label for="avatar">Avatar Utilisateur</label>
        <img src="#" alt="preview avatar" class="previsualisation__img" id="preview-avatar" style="display: none;">
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