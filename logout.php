<?php
require_once __DIR__ . '/config/init.php';

// destro all session and redirect user to homepage
session_destroy();
header('location: ' . ROOT_URL);
die();