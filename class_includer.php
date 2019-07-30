<?php
require_once 'properties.php';
require_once './lang/'.LANG.'.php';

/**
 * Scans dir and finds any level folders
 * Returns string for set_include_path
 */
function incDir(string $dir) {
    $res = PATH_SEPARATOR . $dir;
    $list = scandir($dir);
    array_shift($list);
    array_shift($list);
    foreach ($list as $l) {
        $file = $dir . '/' . $l;
        if (is_dir($file)) {
            $res .= incDir($file);
        }
    }
    return $res;
}

$inc  = incDir('./classes');
set_include_path(get_include_path().$inc);
spl_autoload_extensions('.php,_abs.php,_int.php,_trait.php');
spl_autoload_register();
