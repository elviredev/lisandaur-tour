<?php
require_once __DIR__ . '/config/init.php';
require_once __DIR__.'/utils/sanitize.php';
require_once __DIR__.'/utils/token.php';
require_once __DIR__.'/utils/truncate-text.php';

$page_title = "Articles par pays";
include 'partials/header.php';

// fetch posts if id country is set
if (isset($_GET['id'])) {
  $id = sanitizeInt($_GET['id']);

  // récupérer le country seul
  $country_query = $connection->prepare("SELECT title, flag FROM countries WHERE id = ?");
  $country_query->bind_param("i", $id);
  $country_query->execute();
  $country_result = $country_query->get_result();
  $country = $country_result->fetch_assoc();

  if (!$country) {
    // Aucun pays trouvé pour cet ID
    header('Location: ' . ROOT_URL . 'blog.php');
    exit;
  }

  $country_query->close();

  // récupérer les posts liés à ce country
  $posts_query = $connection->prepare("
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
    WHERE country_id = ?
    ORDER BY posts.date_time DESC
  ");
  $posts_query->bind_param("i", $id);
  $posts_query->execute();
  $posts_result = $posts_query->get_result();
  $posts = $posts_result->fetch_all(MYSQLI_ASSOC);
  $posts_query->close();
} else {
  header('location: ' . ROOT_URL . 'blog.php');
  exit;
}

// fetch all countries
$countries_query = $connection->prepare("SELECT * FROM countries");
$countries_query->execute();
$countries = $countries_query->get_result();
$countries_query->close();
?>

<!-- HEADER START -->
<header class="country__title">
  <h2><?= e($country['title']) ?></h2>
  <img src="<?= ROOT_URL ?>images/flags/<?= e($country['flag']) ?>" class="country__flag" alt="flag">
</header>
<!-- HEADER END -->


<!-- SECTION POSTS START -->
<?php if ($posts): ?>
<section class="posts">
  <div class="container posts__container">
    <?php foreach ($posts as $post): ?>
      <article class="post">
      <div class="post__thumbnail">
        <img src="<?= ROOT_URL ?>images/posts/<?= e($post['thumbnail']) ?>" alt="blog">
      </div>

      <div class="post__info">
        <a href="<?= ROOT_URL ?>country-posts.php?id=<?= e($post['country_id']) ?>" class="country country__button">
          <span><?= e($post['country_title']) ?></span>
          <img src="<?= ROOT_URL ?>images/flags/<?= e($post['country_flag']) ?>" class="country__flag" alt="flag">
        </a>
        <span class="year__button"><?= e($post['year']) ?></span>
        <h3 class="post__title">
          <a href="<?= ROOT_URL ?>post.php/?id=<?= e($post['id']) ?>">
            <?= e($post['title']) ?>
          </a>
        </h3>
        <p class="post__body">
          <?= truncateText($post['body'], 150) ?>
        </p>
      </div>

      <div class="post__author">
        <div class="post__author-avatar">
          <img src="<?= ROOT_URL ?>images/avatars/<?= $post['author_avatar'] ?>" alt="avatar">
        </div>
        <div class="post__author-info">
          <h5>Par : <?= $post['author_firstname'] . ' ' . $post['author_lastname'] ?></h5>
          <small>
            <?= date("d M Y - H:i", strtotime($post['date_time'])) ?>
          </small>
        </div>
      </div>
    </article>
    <?php endforeach; ?>
  </div>

</section>
<?php else: ?>
  <div class="alert__message error lg">Aucun article trouvé pour cette catégorie</div>
<?php endif; ?>
<!-- SECTION POSTS END -->

<!-- SECTION COUNTRY START -->
<section class="country__buttons">
  <div class="container country__buttons-container">
    <?php foreach ($countries as $country): ?>
      <?php if ($country['id'] !== UNCATEGORIZED_COUNTRY_ID): ?>
        <a href="<?= ROOT_URL ?>country-posts.php?id=<?= e($country['id']) ?>" class="country country__button">
          <span><?= e($country['title']) ?></span>
          <img class="country__flag" src="<?= ROOT_URL ?>images/flags/<?= e($country['flag']) ?>" alt="flag">
        </a>
      <?php endif; ?>
    <?php endforeach; ?>
  </div>
</section>
<!-- SECTION COUNTRY END -->


<?php
include 'partials/footer.php'
?>