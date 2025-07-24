<?php
/**
 * @desc tronquer proprement le contenu d’un post sans couper les mots
 * @param $text
 * @param int $limit
 * @param string $end
 * @return string
 */
function truncateText($text, int $limit = 300, string $end = '...'): string
{
  $text = strip_tags($text); // supprime tout le HTML
  $text = trim($text);

  if(mb_strlen($text, 'UTF-8') <= $limit) {
    return htmlspecialchars($text);
  }

  // tronquer à la limite
  $truncated = mb_substr($text, 0, $limit, 'UTF-8');

  // Trouver le dernier espace pour ne pas couper un mot
  $last_space = mb_strrpos($truncated, ' ', 0, 'UTF-8');
  if($last_space !== false) {
    $truncated = mb_substr($truncated, 0, $last_space, 'UTF-8');
  }
  return htmlspecialchars($truncated . $end);
}