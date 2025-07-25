<?php
require_once __DIR__ . '/config/init.php';
require_once __DIR__.'/utils/token.php';
require_once __DIR__.'/utils/sanitize.php';
require_once __DIR__.'/utils/truncate-text.php';

$page_title = "Rechercher un article";
include 'partials/header.php';

$csrf_token = generateCSRFToken('load_more_posts_token');

// fetch 9 posts from posts table
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
  ORDER BY posts.date_time DESC
  LIMIT 9
");
$posts_query->execute();
$posts = $posts_query->get_result();
$posts_query->close();

// total posts
$count_query = $connection->prepare("SELECT COUNT(*) as total FROM posts");
$count_query->execute();
$count_result = $count_query->get_result();
$total_posts = $count_result->fetch_assoc()['total'];
$count_query->close();

// fetch all countries
$query = $connection->prepare("SELECT * FROM countries");
$query->execute();
$countries = $query->get_result();
$query->close();
?>

<!-- SEARCH START -->
<section class="search__bar">
  <form action="<?= ROOT_URL ?>search.php" class="container search__bar-container" method="GET">
    <div>
      <i class="uil uil-search"></i>
      <input type="search" name="search" placeholder="Rechercher...">
    </div>
    <button type="submit" class="btn-search">GO</button>
  </form>
</section>
<!-- SEARCH END -->

<!-- SECTION POSTS START -->
<section class="posts">
  <div class="container posts__container">
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
  </div>
  <?php if ($total_posts > 9) : ?>
    <div style="text-align: center; margin-top: 2rem;">
      <button
          id="load-more"
          class="btn btn-1"
          data-offset="9"
          data-token="<?= $csrf_token ?>"
      >Afficher plus</button>
    </div>
  <?php else: ?>
      <div style="text-align: center; margin-top: 2rem;">
        <p>Pas d'autres articles publiés pour l'instant</p>
      </div>
  <?php endif; ?>
</section>
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