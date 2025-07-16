<?php
// valider les images
function validate_image($file, $max_size = 2_000_000): ?string {
  $allowed_extensions = ["png", "jpg", "jpeg", "svg", "webp"];
  $extension = strtolower(pathinfo($file["name"], PATHINFO_EXTENSION));

  if (!in_array($extension, $allowed_extensions)) {
    return "Format image invalide (jpg, jpeg, png, svg, webp)";
  }

  if ($file["size"] > $max_size) {
    return "Image trop lourde (> " . round($max_size / 1_000_000) . "MB)";
  }

  return null;
}