<?php
require_once __DIR__. '/../config/init.php';
require_once __DIR__. '/../utils/admin-only.php';
require_once __DIR__. '/../utils/redirect-msg.php';
require_once __DIR__. '/../utils/sanitize.php';
require_once __DIR__. '/../utils/pagination-utils.php';

// protection: methode POST uniquement
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['id'], $_POST['csrf_token_delete_country'])) {
  // Vérification du token CSRF
  if (!hash_equals($_SESSION['csrf_token_delete_country'], $_POST['csrf_token_delete_country'])) {
    redirectWithMessage(ROOT_URL . 'admin/manage-countries.php', 'delete-country', 'CSRF token invalide 🔐');
  }

  $id = sanitizeInt($_POST['id']);

  if(!$id) {
    redirectWithMessage(ROOT_URL . 'admin/manage-countries.php', 'delete-country', 'ID pays incorrect ❌');
  }

  // empêcher suppression du country "Uncategorized" (pour les articles sans pays lié)
  if ($id === UNCATEGORIZED_COUNTRY_ID) {
    redirectWithMessage(ROOT_URL . 'admin/manage-countries.php', 'delete-country', 'Le pays "Uncategorized" (non classé) ne peut pas être supprimé 🚫');
  }

  // récupérer country en bdd
  $stmt = $connection->prepare("SELECT title, description, flag FROM countries WHERE id = ?");
  $stmt->bind_param("i", $id);
  $stmt->execute();
  $result = $stmt->get_result();

  // vérifier que le country existe
  if ($result->num_rows !== 1) {
    redirectWithMessage(ROOT_URL . 'admin/manage-countries.php', 'delete-country', 'Ce pays n\'existe pas ❌');
  }

  $country = $result->fetch_assoc();
  $stmt->close();

  // réaffecter les posts au country "uncategorized"
  $stmt = $connection->prepare("UPDATE posts SET country_id = ? WHERE country_id = ?");
  $stmt->bind_param("ii", $uncategorized_id,  $id);
  $uncategorized_id = UNCATEGORIZED_COUNTRY_ID;
  $update_success = $stmt->execute();
  $stmt->close();

  // suppression du flag s'il existe
  if(!empty($country['flag'])) {
    $flag_path = '../images/flags/' . basename($country['flag']);
    if (file_exists($flag_path)) {
      unlink($flag_path);
    }
  }

  if($update_success) {
    // Suppression de la catégorie uniquement si la mise à jour a réussi
    $stmt = $connection->prepare("DELETE FROM countries WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();

    // résultat de la suppression
    if ($stmt->affected_rows > 0) {
      $_SESSION['delete-country-success'] = "{$country['title']} supprimé avec succès 😊";
    } else {
      $_SESSION['delete-country'] = "{$country['title']} ne peut être supprimé 😢";
    }

    $stmt->close();
  } else {
    $_SESSION['delete-post'] = "Échec de la réaffectation des publications avant la suppression du pays ❌";
  }

  unset($_SESSION['csrf_token_delete_country']);

} else {
  // requête non autorisée
  redirectWithMessage(ROOT_URL . 'admin/manage-countries.php', 'delete-country', 'Cette requête n\'est pas autorisée 🚫');
}

// pagination : rester sur la page courante ou revenir à la page précédente
$page = isset($_POST['page']) ? (int) $_POST['page'] : 1;
$total_pages = getTotalPages($connection, 'countries', '', 5);

// redirection logique
$page = min($page, $total_pages);
header('location: ' . ROOT_URL . 'admin/manage-countries.php?page=' .$page);
exit;