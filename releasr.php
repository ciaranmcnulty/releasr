#!/usr/bin/php
<?php

set_include_path(get_include_path() . PATH_SEPARATOR . dirname(realpath(__FILE__)));
require_once('includes.php');

$config = dirname(realpath(__FILE__)).'/config/releasr.conf';

try{
    $arguments = array_slice($_SERVER['argv'], 1);

	$repoConfig = new Releasr_Repo_Config($config);

	$lister = new Releasr_Release_Lister($repoConfig);
	$reviewer = new Releasr_Release_Reviewer($repoConfig, $lister);
	$preparer = new Releasr_Release_Preparer($repoConfig);

    $runner = new Releasr_CliCommand_Main(array(
    	'list' => new Releasr_CliCommand_List($lister),
    	'review' => new Releasr_CliCommand_Review($reviewer),
		'prepare' => new Releasr_CliCommand_Prepare($preparer)
    ));

    echo $runner->run($arguments), PHP_EOL;
}
catch(Exception $e)
{
	echo 'Error: ';
    echo $e->getMessage(), PHP_EOL;
}