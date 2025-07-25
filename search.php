<?php
require_once __DIR__ . '/config/init.php';
require_once __DIR__.'/utils/token.php';
require_once __DIR__.'/utils/sanitize.php';
require_once __DIR__.'/utils/truncate-text.php';

$page_title = "Recherche";
include 'partials/header.php';

$search = isset($_GET['search']) ? trim($_GET['search']) : '';

if (!$search) {
  header('location: ' . ROOT_URL . 'blog.php');
  exit;
}

// préparer un seul placeholder qui sera binder 2 fois pour la recherche par titre et par mot-clé dans le contenu de l'article
$search_term = "%$search%";

$query = $connection->prepare("
    SELECT posts.*, 
         countries.id AS country_id, 
         countries.title AS country_title,
         countries.flag AS country_flag,
         users.id AS author_id,
         users.firstname AS author_firstname,
         users.lastname AS author_lastname,
         users.avatar AS author_avatar
    FROM posts
    JOIN countries ON posts.country_id = countries.id
    JOIN users ON posts.author_id = users.id
    WHERE posts.title LIKE ?
    OR posts.body LIKE ?
    OR posts.year LIKE ?
    ORDER BY posts.date_time DESC
");
$query->bind_param('sss', $search_term, $search_term, $search_term);
$query->execute();
$posts = $query->get_result();
$query->close();
?>


<!-- SECTION POSTS START -->
<section class="posts section__extra-margin">
  <div class="container posts__container">
    <?php if ($posts->num_rows > 0): ?>
      <?php foreach ($posts as $post): ?>
        <article class="post">
          <div class="post__thumbnail">
            <img src="<?= ROOT_URL ?>images/posts/<?= e($post['thumbnail']) ?>" alt="blog">
          </div>

          <div class="post__info">
            <a href="<?= ROOT_URL ?>country-posts.php?id=<?= $post['country_id'] ?>" class="country country__button">
              <span><?= e($post['country_title']) ?></span>
              <img class="country__flag" src="<?= ROOT_URL ?>images/flags/<?= e($post['country_flag']) ?>" alt="flag">
            </a>
            <span class="year__button"><?= e($post['year']) ?></span>
            <h3 class="post__title">
              <a href="<?= ROOT_URL ?>post.php?id=<?= $post['id'] ?>"><?= e($post['title']) ?></a>
            </h3>
            <p class="post__body">
              <?= truncateText($post['body'], 150) ?>
            </p>
          </div>

          <div class="post__author">
            <div class="post__author-avatar">
              <img src="<?= ROOT_URL ?>images/avatars/<?= e($post['author_avatar']) ?>" alt="avatar">
            </div>
            <div class="post__author-info">
              <h5>Par : <?= e($post['author_firstname'] . ' ' . $post['author_lastname']) ?></h5>
              <small><?= date("d M Y - H:i", strtotime($post['date_time'])) ?></small>
            </div>
          </div>
        </article>
      <?php endforeach; ?>
    <?php else: ?>
      <div class="alert__message error lg">
        <p>Aucun résultat trouvé pour <strong><?= e($search) ?></strong></p>
      </div>
    <?php endif; ?>
  </div>
</section>
<!-- SECTION POSTS END -->



<?php
include 'partials/footer.php'
?>