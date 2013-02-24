<?php

/**
* Configuration script that sets up all the objects and returns a CliCommand runner
* @package Releasr
*/


// include everything
set_include_path(get_include_path() . PATH_SEPARATOR . dirname(realpath(__FILE__)));
require_once('classes/includes.php');

// multiple paths for the repo config
$repoConfig = new Releasr_Repo_Config(array(
    '/etc/releasr.conf',
    dirname(realpath(__FILE__)).'/config/releasr.conf',
));

// objects that do the actual work
$lister = new Releasr_Release_Lister($repoConfig);
$reviewer = new Releasr_Release_Reviewer($repoConfig, $lister);
$preparer = new Releasr_Release_Preparer($repoConfig);

// cli command wrappers
$commands = array(
    'list' => new Releasr_CliCommand_Project_List($lister),
    'review' => new Releasr_CliCommand_Project_Review($reviewer),
    'prepare' => new Releasr_CliCommand_Project_Prepare($preparer)
);
return new Releasr_CliCommand_Meta_Main(array_merge($commands, array(
    'help' => new Releasr_CliCommand_Meta_Help($commands)
)));
