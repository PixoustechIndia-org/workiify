<?php
// DB Params - NEVER HARDCODE IN PRODUCTION, USE ENV VARIABLES
// Using define for local development, ensure to secure this for production
/* define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'workiify'); */
define('DB_HOST', 'localhost');
define('DB_USER', 'thms');
define('DB_PASS', 'xp]nq=NGFrnY');
define('DB_NAME', 'workiify');
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
