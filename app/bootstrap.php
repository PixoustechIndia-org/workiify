<?php
// Load Config
require_once 'config/config.php';

// ---- Security defaults (no visible effect on the site) ----
// Session cookie: not readable by JavaScript, not sent with cross-site requests, HTTPS-only when on HTTPS,
// and the server never accepts a session id it didn't create itself.
if (session_status() === PHP_SESSION_NONE) {
    ini_set('session.use_strict_mode', '1');
    ini_set('session.use_only_cookies', '1');
    ini_set('session.cookie_httponly', '1');
    ini_set('session.cookie_samesite', 'Lax');
    if (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') {
        ini_set('session.cookie_secure', '1');
    }
}
// Visitors never see PHP error details (file paths, SQL); errors go to the server log instead.
// Set APP_DEBUG to true in app/config/config.local.php (or the APP_DEBUG env var) while developing.
ini_set('display_errors', APP_DEBUG ? '1' : '0');
ini_set('log_errors', '1');
if (!headers_sent()) {
    header_remove('X-Powered-By');
}

// Autoload Core Libraries
spl_autoload_register(function($className){
    require_once 'libraries/' . $className . '.php';
});
