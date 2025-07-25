<?php
require_once __DIR__ . '/config/init.php';
require_once __DIR__.'/utils/token.php';
require_once __DIR__.'/utils/sanitize.php';
require_once __DIR__.'/utils/truncate-text.php';

$page_title = "Accueil";
include 'partials/header.php';

$csrf_token = generateCSRFToken('load_more_posts_token');

// fetch featured post from bdd
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
  WHERE posts.is_featured = 1
  LIMIT 1
");
$query->execute();
$result = $query->get_result();
$featured_post = $result->fetch_assoc();
$query->close();

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

// total posts for "Afficher Plus"
$count_query = $connection->prepare("SELECT COUNT(*) as total FROM posts");
$count_query->execute();
$count_result = $count_query->get_result();
$total_posts = $count_result->fetch_assoc()['total'];
$count_query->close();

// fetch all countries
$countries_query = $connection->prepare("SELECT * FROM countries");
$countries_query->execute();
$countries = $countries_query->get_result();
$countries_query->close();

?>

<!-- SECTION FEATURED START -->
<?php if ($result->num_rows == 1): ?>
<section class="featured">
  <div class="container featured__container">
    <div class="post__thumbnail featured__thumbnail-wrapper">
      <img
          src="<?= ROOT_URL ?>images/posts/<?= e($featured_post['thumbnail']) ?>"
          alt="<?= e($featured_post['title']) ?>"
      >
      <div class="featured__tag">Article à la une</div>
    </div>

    <div class="post__info">
      <div>
        <a href="<?= ROOT_URL ?>country-posts.php?id=<?= $featured_post['country_id'] ?>" class="country country__button">
          <span><?= e($featured_post['country_title']) ?></span>
          <img class="country__flag" src="<?= ROOT_URL ?>images/flags/<?= e($featured_post['country_flag']) ?>" alt="flag">
        </a>
        <span class="year__button"><?= e($featured_post['year']) ?></span>
      </div>

      <h2 class="post__title">
        <a href="<?= ROOT_URL ?>post.php?id=<?= $featured_post['id'] ?>">
          <?= e($featured_post['title']) ?>
        </a>
      </h2>
      <p class="post__body">
        <?= truncateText($featured_post['body']) ?>
      </p>
      <div class="post__author">
        <div class="post__author-avatar">
          <img src="<?= ROOT_URL ?>images/avatars/<?= e($featured_post['author_avatar']) ?>" alt="avatar">
        </div>

        <div class="post__author-info">
          <h5>Par : <?= e($featured_post['author_firstname'] . ' ' . $featured_post['author_lastname']) ?></h5>
          <small>
            <?= date("d M Y - H:i", strtotime($featured_post['date_time'])) ?>
          </small>
        </div>
      </div>
    </div>
  </div>
</section>
<?php endif; ?>
<!-- SECTION FEATURED END -->

<!-- SECTION POSTS START -->
<section class="posts <?= $featured_post ? '' : 'section__extra-margin' ?>">
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

  <?php if ($total_posts > 9): ?>
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
include 'partials/footer.php';
?>

