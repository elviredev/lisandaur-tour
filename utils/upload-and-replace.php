<?php
// télécharger une nouvelle image et remplacer l'ancienne image
function uploadAndReplace($file, $oldFilename, $folder): ?string {
  if ($file && $file['name']) {
    $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
    $unique = time();

    try {
      $unique .= '_' . bin2hex(random_bytes(5));
    } catch (Exception $e) {
      // fallback en cas d'erreur sur certs serveurs en prod (rare)
      $unique .= '_' . bin2hex(openssl_random_pseudo_bytes(5));
    }

    $newName = $unique . '.' . $extension;
    $destination = rtrim($folder, '/') . '/' . $newName;

    if (move_uploaded_file($file['tmp_name'], $destination)) {
      // supprimer l'ancienne image
      if ($oldFilename && file_exists(rtrim($folder, '/') . '/' . $oldFilename)) {
        unlink(rtrim($folder, '/') . '/' . $oldFilename);
      }
      return $newName;
    }
  }
  return $oldFilename;
}