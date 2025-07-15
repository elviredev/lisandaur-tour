<?php
$page_title = "Article par pays";
include 'partials/header.php'
?>

<!-- HEADER START -->
<header class="country__title">
  <h2>Islande</h2>
  <img class="country__flag" src="images/islande.png" alt="flag">
</header>
<!-- HEADER END -->


<!-- SECTION POSTS START -->
<section class="posts">
  <div class="container posts__container">
    <article class="post">
      <div class="post__thumbnail">
        <img src="images/iceland-1.webp" alt="blog">
      </div>

      <div class="post__info">
        <a href="country-posts.html" class="country country__button">
          <span>Islande</span>
          <img class="country__flag" src="images/islande.png" alt="flag">
        </a>
        <span class="year__button">2017</span>
        <h3 class="post__title">
          <a href="post.php">Lorem ipsum dolor 🐧</a>
        </h3>
        <p class="post__body">
          Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Aenean commodo ligula eget dolor. Aenean massa.
        </p>
      </div>

      <div class="post__author">
        <div class="post__author-avatar">
          <img src="images/02.png" alt="avatar">
        </div>
        <div class="post__author-info">
          <h5>Par : Paul Smarties</h5>
          <small>10 dec, 2021 - 14:56</small>
        </div>
      </div>
    </article>
    <article class="post">
      <div class="post__thumbnail">
        <img src="images/iceland-2.jpg" alt="blog">
      </div>

      <div class="post__info">
        <a href="country-posts.html" class="country country__button">
          <span>Islande</span>
          <img class="country__flag" src="images/islande.png" alt="flag">
        </a>
        <span class="year__button">2025</span>
        <h3 class="post__title">
          <a href="post.php">Lorem ipsum dolor sit amet, consectetur adipisicing elit.</a>
        </h3>
        <p class="post__body">
          Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Aenean commodo ligula eget dolor. Aenean massa. Cum sociis natoque penatibus et magnis dise...
        </p>
      </div>

      <div class="post__author">
        <div class="post__author-avatar">
          <img src="images/03.png" alt="avatar">
        </div>
        <div class="post__author-info">
          <h5>Par : Samantha Smith</h5>
          <small>22 jan, 2025 - 14:15</small>
        </div>
      </div>
    </article>
    <article class="post">
      <div class="post__thumbnail">
        <img src="images/iceland-3.jpg" alt="blog">
      </div>

      <div class="post__info">
        <a href="country-posts.html" class="country country__button">
          <span>Islande</span>
          <img class="country__flag" src="images/islande.png" alt="flag">
        </a>
        <span class="year__button">2021</span>
        <h3 class="post__title">
          <a href="post.php">Lorem ipsum dolor sit amet, consectetur adipisicing</a>
        </h3>
        <p class="post__body">
          Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Aenean commodo ligula eget dolor. Aenean massa. Cum sociis natoque penatibus et magnis dise...
        </p>
      </div>

      <div class="post__author">
        <div class="post__author-avatar">
          <img src="images/02.png" alt="avatar">
        </div>
        <div class="post__author-info">
          <h5>Par : Paul Smarties</h5>
          <small>22 jan, 2025 - 14:15</small>
        </div>
      </div>
    </article>
  </div>

  <div style="text-align: center; margin-top: 2rem;">
    <button
        id="load-more"
        class="btn btn-1"
        data-offset=""
        data-token=""
    >Afficher plus</button>
  </div>
</section>
<!-- SECTION POSTS END -->

<!-- SECTION COUNTRY START -->
<section class="country__buttons">
  <div class="container country__buttons-container">
    <a href="country-posts.html" class="country country__button">
      <span>Japon</span>
      <img class="country__flag" src="images/japon.png" alt="flag">
    </a>
    <a href="country-posts.html" class="country country__button">
      <span>Angleterre</span>
      <img class="country__flag" src="images/royaume-uni.png" alt="flag">
    </a>
    <a href="country-posts.html" class="country country__button">
      <span>Italie</span>
      <img class="country__flag" src="images/italie.png" alt="flag">
    </a>
    <a href="country-posts.html" class="country country__button">
      <span>Islande</span>
      <img class="country__flag" src="images/islande.png" alt="flag">
    </a>
    <a href="country-posts.html" class="country country__button">
      <span>Corée du sud</span>
      <img class="country__flag" src="images/coree-du-sud.png" alt="flag">
    </a>
    <a href="country-posts.html" class="country country__button">
      <span>Espagne</span>
      <img class="country__flag" src="images/espagne.png" alt="flag">
    </a>
    <a href="country-posts.html" class="country country__button">
      <span>Madeire</span>
      <img class="country__flag" src="images/portugal.png" alt="flag">
    </a>
    <a href="country-posts.html" class="country country__button">
      <span>Etats-Unis</span>
      <img class="country__flag" src="images/etats-unis.png" alt="flag">
    </a>
  </div>
</section>
<!-- SECTION COUNTRY END -->


<?php
include 'partials/footer.php'
?>