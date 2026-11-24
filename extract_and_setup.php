<?php
// extract_and_setup.php
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<div style='font-family:sans-serif;padding:30px;max-width:650px;margin:30px auto;border-radius:12px;background:#f8fafc;border:1px solid #e2e8f0;'>";
echo "<h2 style='color:#0f172a;margin-top:0;'>InfinityFree Auto-Installer</h2>";

$zipFile = __DIR__ . '/deploy.zip';
if (!file_exists($zipFile)) {
    die("<p style='color:#dc2626;'><b>Error:</b> deploy.zip not found in " . htmlspecialchars(__DIR__) . "</p></div>");
}

// 1. Remove old files in htdocs that have backslashes
$dirHandle = opendir(__DIR__);
$removedCount = 0;
while (($file = readdir($dirHandle)) !== false) {
    if ($file === '.' || $file === '..' || $file === 'deploy.zip' || $file === 'extract_and_setup.php') {
        continue;
    }
    // Delete files with backslashes from the previous bad extract
    if (strpos($file, '\\') !== false) {
        @unlink(__DIR__ . '/' . $file);
        $removedCount++;
    }
}
closedir($dirHandle);

echo "<p style='color:#475569;'>Cleaned up {$removedCount} misplaced files from previous attempt.</p>";

// 2. Extract deploy.zip
$zip = new ZipArchive();
if ($zip->open($zipFile) === true) {
    $zip->extractTo(__DIR__);
    $zip->close();
    echo "<p style='color:#16a34a;font-weight:bold;'>All project folders and files extracted cleanly!</p>";
} else {
    die("<p style='color:#dc2626;'>Failed to open deploy.zip</p></div>");
}

// 3. Verify public/index.php
if (file_exists(__DIR__ . '/public/index.php')) {
    echo "<div style='padding:16px;background:#ecfdf5;border:1px solid #6ee7b7;border-radius:8px;margin-top:15px;'>";
    echo "<h3 style='color:#065f46;margin-top:0;'>Setup Complete!</h3>";
    echo "<p style='color:#047857;'>Your Campus Resit Portal is ready.</p>";
    echo "<p><a href='/' style='display:inline-block;padding:10px 20px;background:#059669;color:white;text-decoration:none;border-radius:6px;font-weight:bold;'>Open Campus Resit Portal &rarr;</a></p>";
    echo "</div>";
} else {
    echo "<p style='color:#ea580c;'>Extracted, but public/index.php was not detected.</p>";
}

// 4. Ensure chat_messages database table exists
try {
    $envPath = __DIR__ . '/.env';
    if (file_exists($envPath)) {
        $envLines = file($envPath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        $dbConfig = [];
        foreach ($envLines as $line) {
            $line = trim($line);
            if (empty($line) || strpos($line, '#') === 0) continue;
            if (strpos($line, '=') !== false) {
                list($k, $v) = explode('=', $line, 2);
                $dbConfig[trim($k)] = trim($v, " \t\n\r\0\x0B\"'");
            }
        }
        if (!empty($dbConfig['DB_HOST']) && !empty($dbConfig['DB_DATABASE'])) {
            $pdo = new PDO(
                "mysql:host={$dbConfig['DB_HOST']};dbname={$dbConfig['DB_DATABASE']};charset=utf8mb4",
                $dbConfig['DB_USERNAME'] ?? 'root',
                $dbConfig['DB_PASSWORD'] ?? '',
                [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
            );

            $sql = "CREATE TABLE IF NOT EXISTS `chat_messages` (
              `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
              `sender_id` bigint(20) unsigned NOT NULL,
              `receiver_id` bigint(20) unsigned NOT NULL,
              `message` text NOT NULL,
              `is_read` tinyint(1) NOT NULL DEFAULT 0,
              `read_at` timestamp NULL DEFAULT NULL,
              `created_at` timestamp NULL DEFAULT NULL,
              `updated_at` timestamp NULL DEFAULT NULL,
              PRIMARY KEY (`id`),
              KEY `chat_messages_sender_id_receiver_id_index` (`sender_id`,`receiver_id`),
              KEY `chat_messages_receiver_id_is_read_index` (`receiver_id`,`is_read`),
              KEY `chat_messages_created_at_index` (`created_at`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;";

            $pdo->exec($sql);
            echo "<p style='color:#16a34a;'><b>Database:</b> `chat_messages` table verified / created successfully!</p>";
        }
    }
} catch (Exception $e) {
    echo "<p style='color:#eab308;'>Database note: " . htmlspecialchars($e->getMessage()) . "</p>";
}

echo "</div>";
