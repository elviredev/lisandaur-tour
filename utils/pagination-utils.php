<?php
/**
 * @desc Permet une redirection correcte même après suppression du dernier post d'une page
 * @param mysqli $connection
 * @param string $table
 * @param string $where
 * @param int $limit
 * @return int
 */
function getTotalPages(mysqli $connection, string $table, string $where = '', int $limit = 3): int {
  // Sécuriser la requête (pas d'input utilisateur direct dans $table)
  $allowed_tables = ['posts', 'users', 'countries'];
  if (!in_array($table, $allowed_tables, true)) {
    throw new InvalidArgumentException("Table non autorisée : $table");
  }

  $sql = "SELECT COUNT(*) FROM $table";

  if (!empty($where)) {
    $sql .= " WHERE $where";
  }

  $result = mysqli_query($connection, $sql);
  if (!$result) {
    die("Erreur lors du comptage : " . mysqli_error($connection));
  }

  $total = (int) mysqli_fetch_row($result)[0];

  return max(1, (int) ceil($total / $limit)); // toujours au moins une page
}