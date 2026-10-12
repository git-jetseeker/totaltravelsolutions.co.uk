<?php
/**
 * Serve selected static images with long-lived browser cache headers.
 * Used when the host (e.g. SiteGround NGINX Direct Delivery) skips .htaccess
 * Cache-Control for files under /assets/images/.
 */
$file = basename((string) ($_GET['f'] ?? ''));
if ($file === '' || !preg_match('/^[A-Za-z0-9._\-\s]+\.(jpe?g|png|gif|webp|svg)$/i', $file)) {
    http_response_code(404);
    exit('Not found');
}

$path = __DIR__ . '/assets/images/' . $file;
if (!is_file($path)) {
    http_response_code(404);
    exit('Not found');
}

$mtime = filemtime($path) ?: time();
$etag = '"' . md5($file . '|' . $mtime . '|' . filesize($path)) . '"';
$lastModified = gmdate('D, d M Y H:i:s', $mtime) . ' GMT';

if (
    (isset($_SERVER['HTTP_IF_NONE_MATCH']) && trim($_SERVER['HTTP_IF_NONE_MATCH']) === $etag)
    || (isset($_SERVER['HTTP_IF_MODIFIED_SINCE']) && trim($_SERVER['HTTP_IF_MODIFIED_SINCE']) === $lastModified)
) {
    header('HTTP/1.1 304 Not Modified');
    header('Cache-Control: public, max-age=31536000, immutable');
    header('ETag: ' . $etag);
    header('Last-Modified: ' . $lastModified);
    exit;
}

$mime = match (strtolower(pathinfo($file, PATHINFO_EXTENSION))) {
    'jpg', 'jpeg' => 'image/jpeg',
    'png' => 'image/png',
    'gif' => 'image/gif',
    'webp' => 'image/webp',
    'svg' => 'image/svg+xml',
    default => 'application/octet-stream',
};

header('Content-Type: ' . $mime);
header('Content-Length: ' . filesize($path));
header('Cache-Control: public, max-age=31536000, immutable');
header('Expires: ' . gmdate('D, d M Y H:i:s', time() + 31536000) . ' GMT');
header('ETag: ' . $etag);
header('Last-Modified: ' . $lastModified);
header('X-Content-Type-Options: nosniff');

readfile($path);
