<?php
require_once __DIR__ . '/../config/init.php';
require_once __DIR__ . '/../utils/admin-only.php';

$page_title = "Gérer les pays";
include 'partials/header.php'
?>

<!-- SECTION DASHBOARD START -->
<section class="dashboard">
  <div class="container dashboard__container">
    <!-- BUTTON FOR MOBILES -->
    <button id="show__sidebar-btn" class="sidebar__toggle">
      <i class="uil uil-angle-right-b"></i>
    </button>
    <button id="hide__sidebar-btn" class="sidebar__toggle">
      <i class="uil uil-angle-left-b"></i>
    </button>
    <!-- SIDEBAR -->
    <aside>
      <ul>
        <li>
          <a href="add-post.php">
            <i class="uil uil-pen"></i>
            <h5>Ajouter un article</h5>
          </a>
        </li>
        <li>
          <a href="index.php">
            <i class="uil uil-postcard"></i>
            <h5>Gérer les articles</h5>
          </a>
        </li>
        <!-- If user is admin -->
        <?php if(isset($_SESSION['user_is_admin'])): ?>
          <li>
            <a href="add-user.php">
              <i class="uil uil-user-plus"></i>
              <h5>Ajouter un utilisateur</h5>
            </a>
          </li>
          <li>
            <a href="manage-users.php">
              <i class="uil uil-users-alt"></i>
              <h5>Gérer les utilisateurs</h5>
            </a>
          </li>
          <li>
            <a href="add-country.php">
              <i class="uil uil-edit"></i>
              <h5>Ajouter un pays</h5>
            </a>
          </li>
          <li>
            <a href="manage-countries.php" class="active">
              <i class="uil uil-list-ul"></i>
              <h5>Gérer les  pays</h5>
            </a>
          </li>
        <?php endif; ?>
      </ul>
    </aside>

    <!-- CONTENU -->
    <main>
      <h2>Gérer les pays</h2>
      <!-- FORMAT DESKTOP -->
      <table>
        <thead>
        <tr>
          <th>Pays</th>
          <th>Drapeau</th>
          <th>Description</th>
          <th>Editer</th>
          <th>Supprimer</th>
        </tr>
        </thead>
        <tbody>
        <tr>
          <td>Japon</td>
          <td>
            <img class="country__flag" src="images/japon.png" alt="flag">
          </td>
          <td>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Iste labore quia quidem.</td>
          <td><a href="edit-country.php" class="btn sm edit">Editer</a></td>
          <td><a href="#" class="btn sm danger">Suppr</a></td>
        </tr>
        <tr>
          <td>Corée</td>
          <td>
            <img class="country__flag" src="images/coree-du-sud.png" alt="flag">
          </td>
          <td>Lorem ipsum dolor sit amet, consectetur adipisicing elit</td>
          <td><a href="edit-country.php" class="btn sm edit">Editer</a></td>
          <td><a href="#" class="btn sm danger">Suppr</a></td>
        </tr>
        <tr>
          <td>Espagne</td>
          <td>
            <img class="country__flag" src="images/espagne.png" alt="flag">
          </td>
          <td>Lorem ipsum dolor sit amet consectetur 🥘</td>
          <td><a href="edit-country.php" class="btn sm edit">Editer</a></td>
          <td><a href="#" class="btn sm danger">Suppr</a></td>
        </tr>
        </tbody>
      </table>
      <!-- FORMAT MOBILE -->
      <div class="card-mobile__container">
        <div class="card-mobile">
          <p><strong>Pays :</strong> Japon</p>
          <p class="card-mobile__flag-container">
            <strong>Drapeau :</strong>
            <img class="country__flag" src="images/japon.png" alt="flag">
          </p>
          <p><strong>Description :</strong> Lorem ipsum dolor sit amet, consectetur adipisicing elit. Adipisci facilis molestias suscipit?</p>
          <div class="card-actions">
            <a href="edit-country.php" class="btn sm edit">Editer</a>
            <a href="#" class="btn sm danger">Suppr</a>
          </div>
        </div>
        <div class="card-mobile">
          <p><strong>Pays :</strong> Corée</p>
          <p class="card-mobile__flag-container">
            <strong>Drapeau :</strong>
            <img class="country__flag" src="images/coree-du-sud.png" alt="flag">
          </p>
          <p><strong>Description :</strong> Lorem ipsum dolor sit amet, consectetur adipisicing elit</p>
          <div class="card-actions">
            <a href="edit-country.php" class="btn sm edit">Editer</a>
            <a href="#" class="btn sm danger">Suppr</a>
          </div>
        </div>
        <div class="card-mobile">
          <p><strong>Pays :</strong> Espagne</p>
          <p class="card-mobile__flag-container">
            <strong>Drapeau :</strong>
            <img class="country__flag" src="images/espagne.png" alt="flag">
          </p>
          <p><strong>Description :</strong> Lorem ipsum dolor sit amet consectetur 🥘</p>
          <div class="card-actions">
            <a href="edit-country.php" class="btn sm edit">Editer</a>
            <a href="#" class="btn sm danger">Suppr</a>
          </div>
        </div>
      </div>
    </main>
  </div>
</section>
<!-- SECTION DASHBOARD END -->

<?php
include '../partials/footer.php'
?>