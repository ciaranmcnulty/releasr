#!/usr/bin/php
<?php

/**
* Runnable CLI script for Releasr commands
* @package Releasr
*/

try {
    $runner = require_once(dirname(__FILE__) . '/configure.php');
    $arguments = array_slice($_SERVER['argv'], 1);
    echo $runner->run($arguments), PHP_EOL;
}
catch (Releasr_Exception_CliArgs $e) {
    echo 'Error: ', $e->getMessage(), PHP_EOL;
    if ($usage = $e->getUsageMessage()) {
        echo 'Usage: ', $usage, PHP_EOL;
    }
}
catch (Exception $e) {
    echo 'Error: ', $e->getMessage(), PHP_EOL;
}