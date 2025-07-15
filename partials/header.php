<?php
require_once __DIR__ . "/../config/init.php";

?>

<!doctype html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta
      name="viewport"
      content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <!-- Favicon -->
  <link rel="icon" href="/favicon.ico" type="image/x-icon">
  <!--  CUSTOM CSS  -->
  <link rel="stylesheet" href="<?= ROOT_URL ?>css/style.css">
  <!-- ICONSCOUT  -->
  <link rel="stylesheet" href="https://unicons.iconscout.com/release/v4.0.8/css/line.css">
  <title>
      <?= isset($page_title) ? $page_title . ' | LSA-Tour' : 'Accueil | LSA-Tour' ?>
  </title>
</head>
<body>
<!-- HEADER START -->
<header class="header">
  <div class="container">
    <div class="logo">
      <a href="<?= ROOT_URL ?>">
        <span>LSA</span>
        <img src="<?= ROOT_URL ?>images/plane.svg" alt="image logo">
        <span>TOUR</span>
      </a>
    </div>
    <div class="img__profile-menu">
      <div class="avatar">
        <img src="images/01.png" alt="avatar">
      </div>
      <button class="menu-btn">
        <span></span>
        <span></span>
        <span></span>
      </button>
    </div>
  </div>
</header>
<!-- HEADER END -->


<!-- SIDE MENU START -->
<div class="side-menu-overlay"></div>
<div class="side-menu">
  <div class="head">
    <button class="close-btn"></button>
  </div>

  <nav>
    <ul>
      <li><a href="<?= ROOT_URL ?>index.php">Accueil</a></li>
      <li><a href="<?= ROOT_URL ?>blog.php">Blog</a></li>
      <li><a href="<?= ROOT_URL ?>about.php">À propos</a></li>
      <li><a href="<?= ROOT_URL ?>contact.php">Contact</a></li>
      <li><a href="<?= ROOT_URL ?>admin/index.php">Dashboard</a></li>
      <li><a href="<?= ROOT_URL ?>logout.php">Déconnexion</a></li>
<!--      <li><a href="--><?php //= ROOT_URL ?><!--signin.php" class="signin">Se connecter</a></li>-->
      <li class="img__profile">
        <div class="avatar">
          <img src="images/01.png" alt="avatar">
        </div>
      </li>
    </ul>
  </nav>
</div>
<!-- SIDE MENU END -->