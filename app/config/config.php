<?php
// Machine-specific settings (database password, debug switch) live in app/config/config.local.php,
// which is never committed to git (.gitignore: *.local.php). Copy config.local.example.php to
// config.local.php on each server and fill in that server's values. Environment variables work too.
if (is_file(__DIR__ . '/config.local.php')) {
    require __DIR__ . '/config.local.php';
}

// DB Params: defaults are for local XAMPP only
if (!defined('DB_HOST')) define('DB_HOST', getenv('DB_HOST') ?: 'localhost');
if (!defined('DB_USER')) define('DB_USER', getenv('DB_USER') ?: 'root');
if (!defined('DB_PASS')) define('DB_PASS', getenv('DB_PASS') !== false ? getenv('DB_PASS') : '');
if (!defined('DB_NAME')) define('DB_NAME', getenv('DB_NAME') ?: 'workiify');

// Show PHP error details in the browser? Keep false on the live server.
if (!defined('APP_DEBUG')) define('APP_DEBUG', getenv('APP_DEBUG') === '1');

// App Root
define('APPROOT', dirname(dirname(__FILE__)));

// URL Root (Dynamic)
$protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http";
$domain = $_SERVER['HTTP_HOST'];
$dir = str_replace(basename($_SERVER['SCRIPT_NAME']), '', $_SERVER['SCRIPT_NAME']);
// Remove '/public' to keep URLs clean since root .htaccess handles routing
$dir = str_replace('/public/', '/', $dir);
$dir = rtrim($dir, '/');
define('URLROOT', $protocol . '://' . $domain . $dir);

// Site Name
define('SITENAME', 'Workiify');

// Outgoing mail "From" address for enquiry notifications. Uses this
// domain's own name so the mail server doesn't flag it as spoofed; in
// production, point it at a real mailbox on this domain.
define('MAIL_FROM', 'no-reply@' . $domain);
