#!/usr/bin/php
<?php

set_include_path(get_include_path() . PATH_SEPARATOR . dirname(realpath(__FILE__)));
require_once('includes.php');

$config = parse_ini_file(dirname(realpath(__FILE__)).'/config/releasr.conf');

try{
    $arguments = array_slice($_SERVER['argv'], 1);
    $commandConfig = array(
    	'list' => new Releasr_CliCommand_List(
			new Releasr_Release_Lister($config)
		)
    );
    
    $runner = new Releasr_CliCommand_Main($commandConfig);
    echo $runner->run($arguments), PHP_EOL;
}
catch(Exception $e)
{
	echo 'Error: ';
    echo $e->getMessage(), PHP_EOL;
}