<?php
// Database connection data
define('DB_NAME', 'soft_monitor');
define('DB_HOST', 'localhost');
define('DB_LOGIN', 'root');
define('DB_PASS', '');

// Web-site variables
define('ALARM_LEVELS', ['notification', 'minor', 'major', 'critical', 'cleared']);
define('LANG', 'ru');
define('LOG_PERIOD', 86400);
define('TIME_FORMAT', 'y-M-d h:i:s a');
define('PER_PAGE', 30);
define('REQUEST_INTERVAL', 10);
