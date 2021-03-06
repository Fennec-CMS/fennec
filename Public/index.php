<?php

chdir(realpath(__DIR__));

spl_autoload_register(function ($file) {
    $file = __DIR__ . "/../" . str_replace("\\", "/", $file) . ".php";
    require_once($file);
});

session_start();

$fennec = new Fennec\Application();

$fennec->run();
