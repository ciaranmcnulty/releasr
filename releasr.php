#!/usr/bin/php
<?php

/**
* Runnable CLI script for Releasr commands
* @package Releasr
*/

set_include_path(get_include_path() . PATH_SEPARATOR . dirname(realpath(__FILE__)));
require_once('classes/includes.php');

// search path for config files
$configs = array(
    '/etc/releasr.conf',
    dirname(realpath(__FILE__)).'/config/releasr.conf',
);

try {
    $arguments = array_slice($_SERVER['argv'], 1);

    $repoConfig = new Releasr_Repo_Config($configs);

    $lister = new Releasr_Release_Lister($repoConfig);
    $reviewer = new Releasr_Release_Reviewer($repoConfig, $lister);
    $preparer = new Releasr_Release_Preparer($repoConfig);

    $commands = array(
        'list' => new Releasr_CliCommand_Project_List($lister),
        'review' => new Releasr_CliCommand_Project_Review($reviewer),
        'prepare' => new Releasr_CliCommand_Project_Prepare($preparer)
    );

    $runner = new Releasr_CliCommand_Meta_Main(array_merge($commands, array(
        'help' => new Releasr_CliCommand_Meta_Help($commands)
    )));

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