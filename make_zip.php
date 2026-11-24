<?php
// make_zip.php - Create clean Linux-compatible zip with forward slashes
$projectDir = realpath('C:/xampp/htdocs/campus-resit-portal');
$zipFile = $projectDir . '/deploy.zip';

if (file_exists($zipFile)) {
    unlink($zipFile);
}

$zip = new ZipArchive();
if ($zip->open($zipFile, ZipArchive::CREATE | ZipArchive::OVERWRITE) !== true) {
    die("Failed to create zip file\n");
}

$folders = ['app', 'bootstrap', 'config', 'database', 'public', 'resources', 'routes', 'storage', 'vendor'];
$files = ['.htaccess', 'artisan', 'composer.json'];

// Add .env.production as .env
$zip->addFile($projectDir . '/.env.production', '.env');

foreach ($files as $f) {
    $path = $projectDir . '/' . $f;
    if (file_exists($path)) {
        $zip->addFile($path, $f);
    }
}

foreach ($folders as $folder) {
    $dir = $projectDir . '/' . $folder;
    $iterator = new RecursiveIteratorIterator(
        new RecursiveDirectoryIterator($dir, RecursiveDirectoryIterator::SKIP_DOTS),
        RecursiveIteratorIterator::SELF_FIRST
    );

    foreach ($iterator as $item) {
        $fullPath = $item->getPathname();
        $relativePath = substr($fullPath, strlen($projectDir) + 1);
        // Normalize all backslashes to forward slashes for Linux compatibility!
        $normalizedRelativePath = str_replace('\\', '/', $relativePath);

        if ($item->isDir()) {
            $zip->addEmptyDir($normalizedRelativePath);
        } else {
            $zip->addFile($fullPath, $normalizedRelativePath);
        }
    }
}

$zip->close();
echo "Successfully created Linux-compatible deploy.zip: " . filesize($zipFile) . " bytes\n";
