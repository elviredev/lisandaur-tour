<?php
$page_title = "Gérer les utilisateurs";
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
        <li>
          <a href="add-user.php">
            <i class="uil uil-user-plus"></i>
            <h5>Ajouter un utilisateur</h5>
          </a>
        </li>
        <li>
          <a href="manage-users.php" class="active">
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
            <h5>Gérer les  pays</h5>
          </a>
        </li>
      </ul>
    </aside>

    <!-- CONTENU -->
    <main>
      <h2>Gérer les utilisateurs</h2>
      <!-- FORMAT DESKTOP -->
      <table>
        <thead>
        <tr>
          <th>Nom</th>
          <th>Pseudo</th>
          <th>Editer</th>
          <th>Supprimer</th>
          <th>Admin</th>
        </tr>
        </thead>
        <tbody>
        <tr>
          <td>Lilas Castro</td>
          <td>lilas</td>
          <td><a href="edit-user.php" class="btn sm edit">Editer</a></td>
          <td><a href="#" class="btn sm danger">Suppr</a></td>
          <td>Non</td>
        </tr>
        <tr>
          <td>Sandrine Rodriguez</td>
          <td>elviredev</td>
          <td><a href="edit-user.php" class="btn sm edit">Editer</a></td>
          <td><a href="#" class="btn sm danger">Suppr</a></td>
          <td>Oui</td>
        </tr>
        <tr>
          <td>Aurélie Lalart</td>
          <td>aurelie</td>
          <td><a href="edit-user.php" class="btn sm edit">Editer</a></td>
          <td><a href="#" class="btn sm danger">Suppr</a></td>
          <td>Non</td>
        </tr>
        </tbody>
      </table>
      <!-- FORMAT MOBILE -->
      <div class="card-mobile__container">
        <div class="card-mobile">
          <p>
            <strong>Nom :</strong> Lilas Castro
          </p>
          <p><strong>Pseudo :</strong> lilas</p>
          <p><strong>Admin :</strong> Non</p>
          <div class="card-actions">
            <a href="edit-user.php" class="btn sm edit">Editer</a>
            <a href="#" class="btn sm danger">Suppr</a>
          </div>
        </div>
        <div class="card-mobile">
          <p>
            <strong>Nom :</strong> Sandrine Rodriguez
          </p>
          <p><strong>Pseudo :</strong> elviredev</p>
          <p><strong>Admin :</strong> Oui</p>
          <div class="card-actions">
            <a href="edit-user.php" class="btn sm edit">Editer</a>
            <a href="#" class="btn sm danger">Suppr</a>
          </div>
        </div>
        <div class="card-mobile">
          <p>
            <strong>Nom :</strong> Aurélie Lalart
          </p>
          <p><strong>Pseudo :</strong> aurelie</p>
          <p><strong>Admin :</strong> Non</p>
          <div class="card-actions">
            <a href="edit-user.php" class="btn sm edit">Editer</a>
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