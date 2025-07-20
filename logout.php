<?php
require_once __DIR__ . '/config/init.php';

// 1. Vider toutes les variables de session
$_SESSION = [];

// 2. Supprimer le cookie de session (si utilisé)
if (ini_get("session.use_cookies")) {
  $params = session_get_cookie_params();
  setcookie(
    session_name(),
    '',
    time() - 42000,
    $params["path"],
    $params["domain"],
    $params["secure"],
    $params["httponly"]
  );
}

// 3. Détruire la session
session_destroy();

// 4. Redirection vers l'accueil
header('Location: ' . ROOT_URL);
exit;