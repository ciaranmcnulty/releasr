#!/usr/bin/php
<?php

set_include_path(get_include_path() . PATH_SEPARATOR . dirname(realpath(__FILE__)));
require_once('includes.php');

try{
    $arguments = array_slice($_SERVER['argv'], 1);
    $commandConfig = array(
    	'list' => new Releasr_CliCommand_List
    );
    
    $runner = new Releasr_CliCommand_Main($commandConfig);
    $runner->run($arguments);
}
catch(Exception $e)
{
    echo $e->getMessage(), PHP_EOL;
}