<?php
// Exemple d’utilisation :
// require 'partials/pagination.php';
// $pagination = paginate('users', "WHERE NOT id=$current_admin_id", $connection);

function paginate(string $baseQuery, mysqli $connection, int $limit = 5): array {
  // Page courante (par défaut 1 si non précisée)
  $page = isset($_GET['page']) && filter_var($_GET['page'], FILTER_VALIDATE_INT) && $_GET['page'] > 0 ? (int)$_GET['page'] : 1;
  // Calcul de l'offset
  $offset = ($page - 1) * $limit;

  // Récupération des items paginés
  $pageQuery = "$baseQuery LIMIT {$limit} OFFSET {$offset}";
  $itemsResult = mysqli_query($connection, $pageQuery);

  if (!$itemsResult) {
    die("Erreur dans la requête paginée: " . mysqli_error($connection));
  }

  $itemsArray = mysqli_fetch_all($itemsResult, MYSQLI_ASSOC);

  // Requête pour compter le total sans LIMIT/OFFSET
  $countQuery = "SELECT COUNT(*) AS total FROM ($baseQuery) AS counted";
  $countResult = mysqli_query($connection, $countQuery);

  if (!$countResult) {
    die('Erreur dans la requête COUNT : ' . mysqli_error($connection));
  }

  $total = mysqli_fetch_assoc($countResult)['total'];
  $totalPages = ceil($total / $limit);

  return [
    'items' => $itemsArray,
    'page' => $page,
    'total_pages' => $totalPages,
  ];

}