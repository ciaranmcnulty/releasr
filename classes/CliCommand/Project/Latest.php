<?php

/**
 * Shows details of the latest release for a project
 *
 * @package Releasr
 */
class Releasr_CliCommand_Project_Latest extends Releasr_CliCommand_Project_Abstract
{
    /**
    * @var Releasr_Release_Lister
    */
    private $_lister;

    /**
     * @param Releasr_Config The application config
     * @param Releasr_Release_Lister $lister The lister to use
     */
    public function __construct($config, $lister)
    {
        parent::__construct($config);
        $this->_lister = $lister;
    }

    /**
     * Gets detains of the latest release
     *
     * @param array $arguments the CLI arguments the command was run with
     */
    public function run($arguments) 
    {
        $projectName = $this->_getProjectNameFromArguments($arguments);
        if (!$release = $this->_lister->getMostRecentRelease($projectName)) {
            return 'No releases for project "' . $projectName . '"';
        }
        return 'Latest release: ' . $release->name . PHP_EOL . $release->url;
    }

    /**
    * Gets a usage message string
    *
    * @return string The usage message for this command
    */
    public function getUsageMessage()
    {
        $usage = 'releasr latest [projectname]';
        return $usage;
    }

    /**
    * Gets the help message for this command
    */ 
    public function getHelpMessage()
    {
        return 'Shows the details of the latest release for a particular project';
    }
}