<?php
require_once 'class_includer.php';
while (1) {
    $a = new AlarmsProcessor;
    $a->launch($argv[1], $argv[2]);
    sleep(REQUEST_INTERVAL);
}