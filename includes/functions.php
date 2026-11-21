<?php
// Returns the public URL of a destination image, or null if the file is missing.
function destination_image_url(?string $file): ?string
{
    if (!$file) {
        return null;
    }
    $file = basename($file); // blocks ../ tricks
    $path = __DIR__ . '/../assets/images/' . $file;
    return is_file($path) ? '/pothik/assets/images/' . rawurlencode($file) : null;
}