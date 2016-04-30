<?php

define('CLASS_DIR', filter_input(INPUT_SERVER, "DOCUMENT_ROOT") . '/app/includes');
set_include_path(get_include_path() . PATH_SEPARATOR . CLASS_DIR);

spl_autoload_extensions(".php");
spl_autoload_register('autoload');

function autoload($className) {
    $fileName = str_replace("\\", "/", $className) . '.php';
    include $fileName;
}