<?php

/**
 * Nettoie une chaîne de texte (trim uniquement).
 * Ne pas encoder ici, l'encodage est réservé à l'affichage HTML.
 */
function sanitizeText(string $input): string {
  return trim($input);
}

/**
 * Nettoie un entier provenant d'un formulaire (ex : id).
 */
function sanitizeInt($input): int {
  return (int) filter_var($input, FILTER_SANITIZE_NUMBER_INT);
}

/**
 * Nettoie une adresse e-mail.
 * Retourne null si elle est invalide.
 */
function sanitizeEmail(string $input): ?string {
  $email = filter_var(trim($input), FILTER_VALIDATE_EMAIL);
  return $email !== false ? $email : null;
}

/**
 * Nettoie un champ booléen (ex : checkbox).
 * Retourne 1 (true) ou 0 (false).
 */
function sanitizeBool($input): int {
  return filter_var($input, FILTER_VALIDATE_BOOLEAN) ? 1 : 0;
}

/**
 * Nettoie un champ fichier (vérifie qu’il existe et contient bien un nom).
 */
function sanitizeFile(array $file): ?array {
  return isset($file['name']) && $file['name'] !== '' ? $file : null;
}

// 🔐 Sécuriser l'affichage HTML (équivalent de htmlspecialchars)
function e(string $text): string {
  return htmlspecialchars($text, ENT_QUOTES | ENT_HTML5, 'UTF-8');
}

// 🧪 Exemples d'utilisation :

// $firstname = sanitizeText($_POST['firstname'] ?? '');
// $email = sanitizeEmail($_POST['email'] ?? '');
// $user_id = sanitizeInt($_GET['id'] ?? null);
// $is_admin = sanitizeBoolean($_POST['is_admin'] ?? false);
// $avatar = sanitizeFile($_FILES['avatar'] ?? []);

// Affichage sécurisé : <?= e($username)