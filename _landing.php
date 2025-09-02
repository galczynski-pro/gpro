<?php
// Serve standalone landing page HTML without project header/footer
// Uses root-level index.html created by the user

$landingPath = __DIR__ . '/index.html';
if (is_file($landingPath)) {
    readfile($landingPath);
} else {
    // Fallback minimal page if index.html is missing
    header('Content-Type: text/html; charset=UTF-8');
    echo '<!DOCTYPE html><html><head><meta charset="UTF-8"><title>Landing</title></head><body>';
    echo '<h1>Landing page not found</h1>';
    echo '<p>Please add index.html at project root.</p>';
    echo '</body></html>';
}
exit;
?>
