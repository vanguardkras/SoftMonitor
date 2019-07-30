<!DOCTYPE html>
<html>
    <head>
        <script src="/js/jquery.js"></script>
        <script src="/js/functions.js"></script>
        <title><?= HEADER_TITLE ?></title>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta name="description" content="<?= HEADER_DESC ?>" />
        <link type="text/css" rel="stylesheet" href="/css/main.css" />
    </head>
    <body>
        <div class="banner">
            <div class="monitor">
                <div class="mini"></div><div class="key"></div>
            </div>
            <div><h1><a href="/"><?= MAIN_SITE_NAME ?></a></h1></div>
            <?php echo $auth->getLoginForm() ?>
            <?php if ($auth->isAdmin()) { ?>
                <div class="admin_menu">
                    <h4><?= MENU_ADMIN ?></h4>
                    <ul>
                        <li><?= MENU_BASE_SETTINGS ?>:</li>
                        <li><a href="/admin/devices"><?= MENU_DEV_MANAG ?></a></li>
                        <li><a href="/admin/users"><?= MENU_USER_MANAG ?></a></li>
                        <br>
                        <li><?= MENU_ADV_SETTINGS ?>:</li>
                        <li><a href="/admin/defaults"><?= MENU_DEFAULTS ?></a></li>
                        <li><a href="/admin/connections"><?= MENU_CONN_TEMP ?></a></li>
                        <li><a href="/admin/template"><?= MENU_DEV_TEMP ?></a></li>
                    </ul>
                </div>
            <?php } ?>
        </div>
        <menu>
            <li><a href="/alarms"><?= MENU_ALARMS ?></a></li>
            <li><a href="/devices"><?= MENU_DEVICES ?></a></li>
            <li><a href="/log"><?= MENU_LOG ?></a></li>
        </menu>
