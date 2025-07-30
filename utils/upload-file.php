<?php
/**
 * @desc Télécharger un fichier et le stocker dans un dossier
 * @param $file
 * @param $folder
 * @return string|null
 */
function uploadFile($file, $folder): ?string
{
  // créer le dossier s'il n'existe pas
  if (!is_dir($folder)) {
    mkdir($folder, 0755, true);
  }

  $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
  $unique = time();

  try {
    $unique .= '_' . bin2hex(random_bytes(5));
  } catch (Exception $e) {
    // fallback en cas d'erreur sur certs serveurs en prod (rare)
    $unique .= '_' . bin2hex(openssl_random_pseudo_bytes(5));
  }
  $newName = $unique . '.' . $extension;
  $destination = $folder . $newName;

  if (move_uploaded_file($file['tmp_name'], $destination)) {
    return $newName;
  }
  return null;
}


