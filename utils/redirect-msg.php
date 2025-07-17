<?php
function redirectWithMessage(string $location, string $session_key, string $message): never
{
    $_SESSION[$session_key] = $message;
    header('location: ' . $location);
    exit;
}