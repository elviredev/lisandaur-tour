<?php
include __DIR__ . '/../../partials/header.php';

// si user non connecté, il ne peut pas se rendre sur les pages du TdB
if (!isset($_SESSION['user-id'])) {
  header('location: ' . ROOT_URL . 'signin.php');
  exit;
}
