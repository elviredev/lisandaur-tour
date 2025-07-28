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
  $text = strip_tags($text); // supprime les balises HTML
  $text = html_entity_decode($text, ENT_QUOTES | ENT_HTML5, 'UTF-8'); // décode &nbsp;, &amp;, etc.
  $text = trim($text);

  if(mb_strlen($text, 'UTF-8') <= $limit) {
    return htmlspecialchars($text, ENT_QUOTES | ENT_HTML5, 'UTF-8');
  }

  // tronquer à la limite
  $truncated = mb_substr($text, 0, $limit, 'UTF-8');

  // Trouver le dernier espace pour ne pas couper un mot
  $last_space = mb_strrpos($truncated, ' ', 0, 'UTF-8');
  if($last_space !== false) {
    $truncated = mb_substr($truncated, 0, $last_space, 'UTF-8');
  }
  // réencode proprement à la fin, pour l'affichage HTML.
  return htmlspecialchars($truncated . $end, ENT_QUOTES | ENT_HTML5, 'UTF-8');
}