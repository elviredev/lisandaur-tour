<?php
require_once __DIR__ . '/config/init.php';
require_once __DIR__.'/utils/truncate-text.php';
require_once __DIR__.'/utils/sanitize.php';

// vérifier CSRF
$csrf_token_key = 'load_more_posts_token';
if (!isset($_POST[$csrf_token_key]) || $_POST[$csrf_token_key] !== $_SESSION[$csrf_token_key]) {
  http_response_code(403);
  exit('CSRF token invalide 🔐');
}

// s'assurer que le navigateur interprète correctement les caractères
header('Content-Type: text/html; charset=UTF-8');

// récupérer l'article à la une s'il existe
$featured_query = $connection->prepare("SELECT id FROM posts WHERE is_featured = 1 LIMIT 1");
$featured_query->execute();
$featured_result = $featured_query->get_result();
$featured_post_id = $featured_result->num_rows ? $featured_result->fetch_assoc()['id'] : null;
$featured_query->close();

// Récupérer et sécuriser l'offset
$offset = isset($_POST['offset']) ? (int)$_POST['offset'] : 0;
// Nombre d'articles à charger à chaque clic
$limit = 9;

// ne pas afficher l'article vedette dans les chargements AJAX
if ($featured_post_id) {
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
  WHERE posts.id != ?
  ORDER BY posts.date_time DESC
  LIMIT ? OFFSET ?
");
  $query->bind_param("iii", $featured_post_id, $limit, $offset);
} else {
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
  ORDER BY posts.date_time DESC
  LIMIT ? OFFSET ?
");
  $query->bind_param("ii", $limit, $offset);
}

$query->execute();
$result = $query->get_result();

// si aucun résultat, renvoyer un code 204 sans afficher de HTML
if ($result->num_rows === 0) {
  http_response_code(204); // No Content
  exit;
}

// sinon afficher les résultats
while($post = $result->fetch_assoc()):
?>
  <article class="post">
    <div class="post__thumbnail">
      <img src="<?= ROOT_URL ?>images/posts/<?= e($post['thumbnail']) ?>" alt="blog">
    </div>

    <div class="post__info">
      <a href="<?= ROOT_URL ?>country-posts.php?id=<?= $post['country_id'] ?>" class="country country__button">
        <span><?= e($post['country_title']) ?></span>
        <img class="country__flag" src="<?= ROOT_URL ?>images/flags/<?= e($post['country_flag']) ?>" alt="flag">
      </a>
      <span class="year__button">2025</span>
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
<?php endwhile; ?>