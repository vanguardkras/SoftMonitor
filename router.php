<?php

if (isset($_POST['ajax'])) {
    //Data process for ajax request.
    $uri = $_POST['uri'];
    parse_str($_POST['get'], $get);
    foreach ($get as $key => $value) {
        $_GET[$key] = $value;
    }
    $_GET['userdata'] = $_POST['session_id'];
    $auth = new Auth;
    $login = $_SESSION['login'];
    $admin = $_SESSION['admin'];
    $group = $_SESSION['group'];
} else {
    $uri = $_SERVER['REQUEST_URI'];
    $login = $auth_login;
    $admin = $auth->isAdmin();
    $group =  $auth->getGroupId();
}

$func = substr($uri, 1);
// Removes get parameters
if (strstr($func, '?')) {
    $func = preg_replace('/\?(.)+/', '', $func);
}

if (preg_match('~^admin/.+~', $func) && $auth->isAdmin()) {
    $func = explode('/', $func);
    $func = $func[1];
    $render = new AdminRenderer($login, $admin, $group);
} else {
    $render = new PagesRenderer($login, $admin, $group);
}

if (method_exists($render, $func)) {
    echo '<div class="'.$func.'">';
    $render->$func();
} elseif ($func == '') {
    echo '<div class="main">';
    $render->main();
} else {
    echo '<div class="error">';
    $render->error();
}
echo '</div>';
