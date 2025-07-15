<?php
$page_title = "Gérer les articles";
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
          <a href="index.php" class="active">
            <i class="uil uil-postcard"></i>
            <h5>Gérer les articles</h5>
          </a>
        </li>
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
          <a href="manage-countries.php">
            <i class="uil uil-list-ul"></i>
            <h5>Gérer les pays</h5>
          </a>
        </li>
      </ul>
    </aside>

    <!-- CONTENU -->
    <main>
      <h2>Gérer les articles</h2>
      <!-- FORMAT DESKTOP -->
      <table>
        <thead>
          <tr>
            <th>Titre</th>
            <th>Pays</th>
            <th>Auteur</th>
            <th>Editer</th>
            <th>Supprimer</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td>Lorem ipsum dolor sit amet, consectetur adipisicing elit.</td>
            <td>Japon</td>
            <td>Lilas</td>
            <td><a href="edit-post.php" class="btn sm edit">Editer</a></td>
            <td><a href="#" class="btn sm danger">Suppr</a></td>
          </tr>
          <tr>
            <td>Lorem ipsum dolor sit amet</td>
            <td>Islande</td>
            <td>elviredev</td>
            <td><a href="edit-post.php" class="btn sm edit">Editer</a></td>
            <td><a href="#" class="btn sm danger">Suppr</a></td>
          </tr>
          <tr>
            <td>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Provident, quidem!</td>
            <td>Italie</td>
            <td>aurélie</td>
            <td><a href="edit-post.php" class="btn sm edit">Editer</a></td>
            <td><a href="#" class="btn sm danger">Suppr</a></td>
          </tr>
        </tbody>
      </table>
      <!-- FORMAT MOBILE -->
      <div class="card-mobile__container">
        <div class="card-mobile">
          <p><strong>Titre :</strong> Lorem ipsum dolor sit amet, consectetur adipisicing elit.</p>
          <p><strong>Pays :</strong> Japon</p>
          <p><strong>Auteur :</strong> Lilas</p>
          <div class="card-actions">
            <a href="edit-post.php" class="btn sm edit">Editer</a>
            <a href="#" class="btn sm danger">Suppr</a>
          </div>
        </div>

        <div class="card-mobile">
          <p><strong>Titre :</strong> Lorem ipsum dolor sit amet</p>
          <p><strong>pays :</strong> Islande</p>
          <p><strong>Auteur :</strong> elviredev</p>
          <div class="card-actions">
            <a href="edit-post.php" class="btn sm edit">Editer</a>
            <a href="#" class="btn sm danger">Suppr</a>
          </div>
        </div>

        <div class="card-mobile">
          <p><strong>Titre :</strong> Lorem ipsum dolor sit amet, consectetur adipisicing elit. Provident, quidem!</p>
          <p><strong>pays :</strong> Italie</p>
          <p><strong>Auteur :</strong> aurélie</p>
          <div class="card-actions">
            <a href="edit-post.php" class="btn sm edit">Editer</a>
            <a href="#" class="btn sm danger">Suppr</a>
          </div>
        </div>
        <div class="card-mobile">
          <p><strong>Titre :</strong> Lorem ipsum dolor sit amet, consectetur adipisicing elit. Provident, quidem!</p>
          <p><strong>pays :</strong> Italie</p>
          <p><strong>Auteur :</strong> aurélie</p>
          <div class="card-actions">
            <a href="edit-post.php" class="btn sm edit">Editer</a>
            <a href="#" class="btn sm danger">Suppr</a>
          </div>
        </div>
        <div class="card-mobile">
          <p><strong>Titre :</strong> Lorem ipsum dolor sit amet, consectetur adipisicing elit. Provident, quidem!</p>
          <p><strong>pays :</strong> Italie</p>
          <p><strong>Auteur :</strong> aurélie</p>
          <div class="card-actions">
            <a href="edit-post.php" class="btn sm edit">Editer</a>
            <a href="#" class="btn sm danger">Suppr</a>
          </div>
        </div>
        <div class="card-mobile">
          <p><strong>Titre :</strong> Lorem ipsum dolor sit amet, consectetur adipisicing elit. Provident, quidem!</p>
          <p><strong>pays :</strong> Italie</p>
          <p><strong>Auteur :</strong> aurélie</p>
          <div class="card-actions">
            <a href="edit-post.php" class="btn sm edit">Editer</a>
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