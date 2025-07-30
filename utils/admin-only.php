<?php
/**
 * @desc Permet le contrôle d'accès
 */
if (!isset($_SESSION['user_is_admin']) || !$_SESSION['user_is_admin']) {
  // redirect to dashboard
  $_SESSION['access-denied'] = "Vous devez être admin pour accèder à cette page";
  header('location: ' . ROOT_URL . 'admin/index.php');
  exit;
}