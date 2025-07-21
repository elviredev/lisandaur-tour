<?php
require_once __DIR__ . '/../config/init.php';
require_once __DIR__ . '/../utils/admin-only.php';
require_once __DIR__.'/../utils/token.php';
require_once __DIR__.'/../utils/sanitize.php';

$page_title = "Gérer les pays";
include 'partials/header.php';
include 'partials/pagination.php';

// Génération d'un token CSRF
$csrf_token = generateCSRFToken('csrf_token_delete_country');

// récupérer les pays
$baseQuery = "SELECT * FROM countries ORDER BY title";
$pagination = paginate($baseQuery, $connection, 3);
$countries = $pagination['items'];
$page = $pagination['page'];
$total_pages = $pagination['total_pages'];
?>

<!-- SECTION DASHBOARD START -->
<section class="dashboard">
  <?php if (isset($_SESSION['add-country-success'])): ?>
    <div class="alert__message success container">
      <p>
        <?= $_SESSION['add-country-success'];
        unset($_SESSION['add-country-success']);
        ?>
      </p>
    </div>
  <?php elseif (isset($_SESSION['add-country'])): ?>
    <div class="alert__message error container">
      <p>
        <?= $_SESSION['add-country'];
        unset($_SESSION['add-country']);
        ?>
      </p>
    </div>
  <?php elseif (isset($_SESSION['edit-country-success'])): ?>
    <div class="alert__message success container">
      <p>
        <?= $_SESSION['edit-country-success'];
        unset($_SESSION['edit-country-success']);
        ?>
      </p>
    </div>
  <?php elseif (isset($_SESSION['edit-country'])): ?>
    <div class="alert__message error container">
      <p>
        <?= $_SESSION['edit-country'];
        unset($_SESSION['edit-country']);
        ?>
      </p>
    </div>
  <?php elseif (isset($_SESSION['delete-country-success'])): ?>
    <div class="alert__message success container">
      <p>
        <?= $_SESSION['delete-country-success'];
        unset($_SESSION['delete-country-success']);
        ?>
      </p>
    </div>
  <?php elseif (isset($_SESSION['delete-country'])): ?>
    <div class="alert__message error container">
      <p>
        <?= $_SESSION['delete-country'];
        unset($_SESSION['delete-country']);
        ?>
      </p>
    </div>
  <?php endif; ?>
  
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
              <i class="uil uil-directions"></i>
              <h5>Ajouter un pays</h5>
            </a>
          </li>
          <li>
            <a href="manage-countries.php">
              <i class="uil uil-globe"></i>
              <h5>Gérer les pays</h5>
            </a>
          </li>
        <?php endif; ?>
      </ul>
    </aside>

    <!-- CONTENU -->
    <main>
      <h2>Gérer les pays</h2>
      <!-- FORMAT DESKTOP -->
      <?php if (count($countries) > 0): ?>
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
        <?php foreach ($countries as $country): ?>
        <tr>
          <td><?= e($country['title']) ?></td>
          <td>
            <img class="country__flag" src="<?= ROOT_URL ?>images/flags/<?= e($country['flag']) ?>" alt="flag">
          </td>
          <td><?= e($country['description']) ?></td>
          <td><a href="<?= ROOT_URL ?>admin/edit-country.php?id=<?= e($country['id']) ?>&page=<?= $page ?>" class="btn sm edit">Editer</a></td>
          <td>
            <form action="<?= ROOT_URL ?>admin/delete-country.php" method="POST" class="delete-country-form" style="display: inline-block;">
              <input type="hidden" name="id" value="<?= e($country['id']) ?>">
              <input type="hidden" name="csrf_token_delete_country" value="<?= $csrf_token ?>">
              <input type="hidden" name="title" value="<?= e($country['title']) ?>">
              <input type="hidden" name="page" value="<?= e($_GET['page'] ?? 1) ?>">

              <button type="submit" class="btn sm danger">Suppr</button>
            </form>
          </td>
        </tr>
        <?php endforeach; ?>
        </tbody>
      </table>
      <!-- FORMAT MOBILE -->
      <div class="card-mobile__container">
        <?php foreach ($countries as $country): ?>
        <div class="card-mobile">
          <p><strong>Pays :</strong> <?= e($country['title']) ?></p>
          <p class="card-mobile__flag-container">
            <strong>Drapeau :</strong>
            <img class="country__flag" src="<?= ROOT_URL ?>images/flags/<?= e($country['flag']) ?>" alt="flag">
          </p>
          <p><strong>Description :</strong> <?= e($country['description']) ?></p>
          <div class="card-actions">
            <a href="<?= ROOT_URL ?>admin/edit-country.php?id=<?= e($country['id']) ?>&page=<?= $page ?>" class="btn sm edit">Editer</a>

            <form action="<?= ROOT_URL ?>admin/delete-country.php" method="POST" class="delete-country-form" style="display: inline-block;">
              <input type="hidden" name="id" value="<?= e($country['id']) ?>">
              <input type="hidden" name="csrf_token_delete_country" value="<?= $csrf_token ?>">
              <input type="hidden" name="title" value="<?= e($country['title']) ?>">
              <input type="hidden" name="page" value="<?= e($_GET['page'] ?? 1) ?>">

              <button type="submit" class="btn sm danger">Suppr</button>
            </form>
          </div>
        </div>
        <?php endforeach; ?>
      </div>
      <?php else: ?>
        <div class="alert__message error">Aucun pays n'a été trouvé</div>
      <?php endif; ?>

      <!-- Pagination -->
      <?php include 'partials/pagination-template.php'; ?>
    </main>
  </div>
</section>
<!-- SECTION DASHBOARD END -->

<?php
include '../partials/footer.php'
?>