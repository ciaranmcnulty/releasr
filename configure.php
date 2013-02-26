<?php

/**
* Configuration script that sets up all the objects and returns a CliCommand runner
* @package Releasr
*/


// include everything
set_include_path(get_include_path() . PATH_SEPARATOR . dirname(realpath(__FILE__)));
require_once('classes/includes.php');

// multiple paths for the config
$config = new Releasr_Config(array(
    '/etc/releasr.conf',
    dirname(realpath(__FILE__)).'/config/releasr.conf',
));

// util objects
$urlResolver = new Releasr_Repo_UrlResolver($config);
$svnRunner = new Releasr_Repo_Runner();

// objects that coordinate the actions
$lister = new Releasr_Controller_Lister($urlResolver, $svnRunner);
$reviewer = new Releasr_Controller_Reviewer($urlResolver, $svnRunner, $lister);
$preparer = new Releasr_Controller_Preparer($urlResolver, $svnRunner);

// cli command wrappers
$commands = array(
    'list' => new Releasr_CliCommand_Project_List($config, $lister),
    'latest' => new Releasr_CliCommand_Project_Latest($config, $lister),
    'review' => new Releasr_CliCommand_Project_Review($config, $reviewer),
    'prepare' => new Releasr_CliCommand_Project_Prepare($config, $preparer)
);
return new Releasr_CliCommand_Meta_Main(array_merge($commands, array(
    'help' => new Releasr_CliCommand_Meta_Help($commands)
)));
