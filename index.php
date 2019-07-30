<?php
require_once 'class_includer.php';

//In case of data is required for an ajax request.
if (isset($_POST['ajax'])) {
    require_once 'router.php';
} else {
//Data view for browser pages with header and footer.
    $auth = new Auth;
    include_once './pages/header.php';
    if ($auth_login = $auth->isAuth()) {
        require_once 'router.php';
    }
    include_once './pages/footer.php';
}

