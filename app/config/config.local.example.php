<?php
// Copy this file to config.local.php (same folder) on each server and fill in that server's values.
// config.local.php is ignored by git, so passwords never end up in the repository.
define('DB_HOST', 'localhost');
define('DB_USER', 'your_db_user');
define('DB_PASS', 'your_db_password');
define('DB_NAME', 'workiify');

// Never true on the live server: it shows PHP error details to visitors.
define('APP_DEBUG', false);
