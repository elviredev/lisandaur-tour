<?php
function generateCSRFToken(string $key): string {
  if (!isset($_SESSION[$key])) {
    try {
      $_SESSION[$key] = bin2hex(random_bytes(32));
    } catch (Exception $e) {
      $_SESSION[$key] = bin2hex(openssl_random_pseudo_bytes(32));
    }
  }
  return $_SESSION[$key];
}