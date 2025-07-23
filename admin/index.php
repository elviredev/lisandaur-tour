<?php
require_once __DIR__ . '/../config/init.php';
require_once __DIR__.'/../utils/token.php';
require_once __DIR__.'/../utils/sanitize.php';

$page_title = "Gérer les articles";
include 'partials/header.php';
include 'partials/pagination.php';

// Génération d'un token CSRF
$csrf_token = generateCSRFToken('csrf_token_delete_post');

// récupérer le user courant
$current_user_id = (int) $_SESSION['user-id'];

// si l'utilisateur est admin, on récupère tous les posts
if (isset($_SESSION['user_is_admin']) && $_SESSION['user_is_admin']) {
  $baseQuery = "SELECT posts.id, posts.title, countries.title AS country_title, users.username AS username
                FROM posts
                JOIN countries ON posts.country_id = countries.id
                JOIN users ON posts.author_id = users.id
                ORDER BY posts.id DESC";
} else {
  // sinon, on ne récupère que les posts du user connecté
  $baseQuery = "SELECT posts.id, posts.title, countries.title AS country_title, users.username AS username
                FROM posts
                JOIN countries ON posts.country_id = countries.id
                JOIN users ON posts.author_id = users.id
                WHERE posts.author_id = $current_user_id
                ORDER BY posts.id DESC";
}

$pagination = paginate($baseQuery, $connection, 3);
$posts = $pagination['items'];
$page = $pagination['page'];
$total_pages = $pagination['total_pages'];
?>

<!-- SECTION DASHBOARD START -->
<section class="dashboard">
  <?php if (isset($_SESSION['access-denied'])): ?>
    <div class="alert__message error container">
      <p>
        <?= $_SESSION['access-denied'];
        unset($_SESSION['access-denied']);
        ?>
      </p>
    </div>
  <?php elseif (isset($_SESSION['add-post-success'])): ?>
  <div class="alert__message success container">
    <p>
      <?= $_SESSION['add-post-success'];
      unset($_SESSION['add-post-success']);
      ?>
    </p>
  </div>
  <?php elseif (isset($_SESSION['add-post'])): ?>
  <div class="alert__message error container">
    <p>
      <?= $_SESSION['add-post'];
      unset($_SESSION['add-post']);
      ?>
    </p>
  </div>
  <?php elseif (isset($_SESSION['edit-post-success'])): ?>
  <div class="alert__message success container">
    <p>
      <?= $_SESSION['edit-post-success'];
      unset($_SESSION['edit-post-success']);
      ?>
    </p>
  </div>
  <?php elseif (isset($_SESSION['edit-post'])): ?>
  <div class="alert__message error container">
    <p>
      <?= $_SESSION['edit-post'];
      unset($_SESSION['edit-post']);
      ?>
    </p>
  </div>
  <?php elseif (isset($_SESSION['delete-post-success'])): ?>
  <div class="alert__message success container">
    <p>
      <?= $_SESSION['delete-post-success'];
      unset($_SESSION['delete-post-success']);
      ?>
    </p>
  </div>
  <?php elseif (isset($_SESSION['delete-post'])): ?>
  <div class="alert__message error container">
    <p>
      <?= $_SESSION['delete-post'];
      unset($_SESSION['delete-post']);
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
          <a href="index.php" class="active">
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
      <h2>Gérer les articles</h2>
      <!-- FORMAT DESKTOP -->
      <?php if(count($posts) > 0): ?>
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
          <?php foreach ($posts as $post): ?>
            <tr>
              <td><?= e($post['title']) ?></td>
              <td><?= e($post['country_title']) ?></td>
              <td><?= e($post['username']) ?></td>
              <td><a href="<?= ROOT_URL ?>admin/edit-post.php?id=<?= e($post['id']) ?>&page=<?= e($page) ?>" class="btn sm edit">Editer</a></td>

              <td><a href="#" class="btn sm danger">Suppr</a></td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
      <!-- FORMAT MOBILE -->
      <div class="card-mobile__container">
        <?php foreach ($posts as $post): ?>
          <div class="card-mobile">
          <p><strong>Titre :</strong> <?= e($post['title']) ?></p>
          <p><strong>Pays :</strong> <?= e($post['country_title']) ?></p>
          <p><strong>Auteur :</strong> <?= e($post['username']) ?></p>
          <div class="card-actions">
            <a href="<?= ROOT_URL ?>admin/edit-post.php?id=<?= e($post['id']) ?>&page=<?= e($page) ?>" class="btn sm edit">Editer</a>

            <a href="#" class="btn sm danger">Suppr</a>
          </div>
        </div>
        <?php endforeach; ?>
      </div>
      <?php else: ?>
        <div class="alert__message error">Aucun article n'a été trouvé</div>
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