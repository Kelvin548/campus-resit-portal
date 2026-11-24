<?php
// InfinityFree Deploy Unzipper
$zipFile = __DIR__ . '/deploy.zip';

if (!file_exists($zipFile)) {
    die("<h3>Error: deploy.zip was not found in " . htmlspecialchars(__DIR__) . "</h3><p>Make sure you uploaded deploy.zip into this same folder.</p>");
}

$zip = new ZipArchive;
$res = $zip->open($zipFile);
if ($res === TRUE) {
    $zip->extractTo(__DIR__);
    $zip->close();
    echo "<div style='font-family:sans-serif;padding:30px;background:#e6fffa;border:1px solid #38b2ac;border-radius:10px;max-width:600px;margin:40px auto;'>";
    echo "<h2 style='color:#234e52;margin-top:0;'>Files Extracted Successfully!</h2>";
    echo "<p style='color:#285e61;'>Your portal files are now in place.</p>";
    echo "<hr style='border:0;border-top:1px solid #b2f5ea;margin:15px 0;'>";
    echo "<p style='color:#e53e3e;font-weight:bold;'>IMPORTANT: Delete <code>unzip.php</code> and <code>deploy.zip</code> from your File Manager now.</p>";
    echo "<p><a href='/' style='display:inline-block;padding:10px 18px;background:#319795;color:white;text-decoration:none;border-radius:6px;font-weight:bold;'>Go to Website &rarr;</a></p>";
    echo "</div>";
} else {
    echo "<h3>Error extracting zip file. Error code: " . htmlspecialchars($res) . "</h3>";
}
