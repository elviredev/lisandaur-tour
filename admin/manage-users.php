<?php
require_once __DIR__ . '/../config/init.php';
require_once __DIR__ . '/../utils/admin-only.php';
require_once __DIR__.'/../utils/token.php';

$page_title = "Gérer les utilisateurs";
include 'partials/header.php';
include 'partials/pagination.php';

// Génération d'un token CSRF
$csrf_token = generateCSRFToken('csrf_token_delete_user');

/**
 * Récupérer les users depuis la bdd mais pas le user courant
 * Requête paginée pour récupérer les users
 */
$current_admin_id = $_SESSION['user-id'];

$baseQuery = "SELECT * FROM users WHERE NOT id = $current_admin_id ORDER BY lastname";
$pagination = paginate($baseQuery, $connection);
$users = $pagination['items'];
$page = $pagination['page'];
$total_pages = $pagination['total_pages'];
?>

<!-- SECTION DASHBOARD START -->
<section class="dashboard">
  <?php if (isset($_SESSION['add-user-success'])): ?>
    <div class="alert__message success container">
      <p>
        <?= $_SESSION['add-user-success'];
        unset($_SESSION['add-user-success']);
        ?>
      </p>
    </div>
  <?php elseif (isset($_SESSION['add-user'])): ?>
    <div class="alert__message error container">
      <p>
        <?= $_SESSION['add-user'];
        unset($_SESSION['add-user']);
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
        <?php endif; ?>
      </ul>
    </aside>

    <!-- CONTENU -->
    <main>
      <h2>Gérer les utilisateurs</h2>
      <!-- FORMAT DESKTOP -->
      <?php if (count($users) > 0): ?>
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
        <?php foreach ($users as $user): ?>
          <tr>
            <td><?= "{$user['firstname']} {$user['lastname']}" ?></td>
            <td><?= $user['username'] ?></td>
            <td><a href="<?= ROOT_URL ?>admin/edit-user.php?id=<?= $user['id'] ?>&page=<?= $page ?>" class="btn sm edit">Editer</a></td>
            <td><a href="#" class="btn sm danger">Suppr</a></td>
            <td><?= $user['is_admin'] ? "Oui" : "Non" ?></td>
          </tr>
        <?php endforeach; ?>
        </tbody>
      </table>

      <!-- FORMAT MOBILE -->
        <div class="card-mobile__container">
          <?php foreach ($users as $user): ?>
            <div class="card-mobile">
            <p>
              <strong>Nom :</strong> <?= "{$user['firstname']} {$user['lastname']}" ?>
            </p>
            <p><strong>Pseudo :</strong> <?= $user['username'] ?></p>
            <p><strong>Admin :</strong> <?= $user['is_admin'] ? "Oui" : "Non" ?></p>
            <div class="card-actions">
              <a href="<?= ROOT_URL ?>admin/edit-user.php?id=<?= $user['id'] ?>&page=<?= $page ?>" class="btn sm edit">Editer</a>
              <a href="#" class="btn sm danger">Suppr</a>
            </div>
          </div>
          <?php endforeach; ?>
        </div>
      <?php else: ?>
        <div class="alert__message error">Aucun utilisateur trouvé</div>
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