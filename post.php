<?php
require_once __DIR__ . '/config/init.php';
require_once __DIR__ . '/utils/sanitize.php';

$page_title = "Article";
include 'partials/header.php';

// fetch post from bdd if id is set
if (isset($_GET['id'])) {
  $id = sanitizeInt($_GET['id']);

  $query = $connection->prepare("
    SELECT posts.*,
           users.id AS author_id, 
           users.firstname AS author_firstname,
           users.lastname AS author_lastname,
           users.avatar AS author_avatar
    FROM posts
    JOIN users ON posts.author_id = users.id
    WHERE posts.id = ?
    LIMIT 1
  ");
  $query->bind_param("i", $id);
  $query->execute();
  $result = $query->get_result();
  $post = $result->fetch_assoc();

  if (!$post) {
    // Aucun article trouvé pour cet ID
    header('Location: ' . ROOT_URL . 'blog.php');
    exit;
  }

  $query->close();
} else {
  header('location: ' . ROOT_URL . 'blog.php');
  exit;
}
?>

<!-- SINGLE POST START -->
<section class="single-post">
  <div class="container single-post__container">
    <h2><?= e($post['title']) ?></h2>

    <div class="post__author">
      <div class="post__author-avatar">
        <img src="<?= ROOT_URL ?>images/avatars/<?= $post['author_avatar'] ?>" alt="avatar">
      </div>

      <div class="post__author-info">
        <h5>Par : <?= e($post['author_firstname'] . ' ' . $post['author_lastname']) ?></h5>
        <small><?= date("d M Y - H:i", strtotime($post['date_time'])) ?></small>
      </div>
    </div>

    <div class="single-post__thumbnail">
      <a data-fslightbox="gallery-one" data-type="image" href="<?= ROOT_URL ?>images/posts/<?= $post['thumbnail'] ?>">
        <img
            src="<?= ROOT_URL ?>images/posts/<?= $post['thumbnail'] ?>"
            alt="<?= e($post['title']) ?>"
        >
      </a>
    </div>
    <?php if (isset($post['image_1']) || isset($post['image_2'])): ?>
      <div class="single-post__images">
        <?php if (isset($post['image_1'])): ?>
          <a href="<?= ROOT_URL ?>images/posts/<?= $post['image_1'] ?>" class="single-post__image" data-fslightbox="gallery-one" data-type="image">
            <img
                src="<?= ROOT_URL ?>images/posts/<?= $post['image_1'] ?>"
                alt="Blog image"
            >
          </a>
        <?php endif; ?>
        <?php if (isset($post['image_2'])): ?>
          <a href="<?= ROOT_URL ?>images/posts/<?= $post['image_2'] ?>" class="single-post__image" data-fslightbox="gallery-one" data-type="image">
            <img
                src="<?= ROOT_URL ?>images/posts/<?= $post['image_2'] ?>"
                alt="Blog image"
            >
          </a>
        <?php endif; ?>
      </div>
    <?php endif; ?>

    <div class="post__body">
      <?= $post['body'] ?>
    </div>

  </div>
</section>
<!-- SINGLE POST END -->



<?php
include 'partials/footer.php'
?>