<?php 
require_once '../classes/service/snmpreq.php';

$db_check = '';
$host_error = '';
$db_host_filler = 'localhost';
$db_login_filler = 'root';
$db_pass_filler = '';
$time = '';
$lang_filler = 'en';
$time_format_filler = 'y-M-d h:i:s a';
$request_interval_filler = 10;
$per_page_filler = 30;

if (isset($_POST['check']) || isset($_POST['install'])) {
    extract($_POST);
    $db_host_filler = $db_host;
    $db_login_filler = $db_login;
    $db_pass_filler = $db_pass;
    $time_format_filler = $time_format;
    $request_interval_filler = $request_interval;
    $per_page_filler = $per_page;
    $dsn = 'mysql:host=' . $db_host . ';charset=utf8';
    $lang_filler = $lang;
}

if (isset($_POST['check'])) {
    if (SnmpReq::ping($db_host) !== false) {
        try {
            $pdo = new pdo($dsn, $db_login, $db_pass);
            $db_check = '<span style="color: greenyellow;">Database connection is successfull.</span>';
        } catch (PDOException $ex) {
            $db_check = 'Database connection error.';
        }
    } else {
        $host_error = 'Host is unreachable';
    }
    $time = date($time_format, time());
}

if (isset($_POST['install'])) {
    $pdo = new pdo($dsn, $db_login, $db_pass);
    $sql = file_get_contents('soft_monitor.sql');
    $pdo->exec($sql);
    
    $prop = '<?php' . PHP_EOL;
    $prop .= '// Database connection data' . PHP_EOL;
    $prop .= "define('DB_NAME', 'soft_monitor');" . PHP_EOL;
    $prop .= "define('DB_HOST', '$db_host');" . PHP_EOL;
    $prop .= "define('DB_LOGIN', '$db_login');" . PHP_EOL;
    $prop .= "define('DB_PASS', '$db_pass');" . PHP_EOL;
    $prop .= PHP_EOL;
    $prop .= "// Web-site variables" . PHP_EOL;
    $prop .= "define('ALARM_LEVELS', ['notification', 'minor', 'major', 'critical', 'cleared']);" . PHP_EOL;
    $prop .= "define('LANG', '$lang');" . PHP_EOL;
    $prop .= "define('LOG_PERIOD', 86400);" . PHP_EOL;
    $prop .= "define('TIME_FORMAT', '$time_format');" . PHP_EOL;
    $prop .= "define('PER_PAGE', $per_page);" . PHP_EOL;
    $prop .= "define('REQUEST_INTERVAL', $request_interval);" . PHP_EOL;
    
    file_put_contents('../properties.php', $prop);
    
    header('Location: /');
    rename('../install', '../installed');
}
include_once 'interface.php';