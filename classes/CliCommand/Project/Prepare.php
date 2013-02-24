<?php

/**
 * Command that screates a new release branch
 *
 * @package Releasr
 */
class Releasr_CliCommand_Project_Prepare extends Releasr_CliCommand_Project_Abstract
{
    /**
     * @var Releasr_Release_Preparer
     */
    private $_preparer;

    /**
     * @param Releasr_Config The application config
     * @var Releasr_Release_Preparer The preparer to use to talk to the repo
     */
    public function __construct($config, $preparer) 
    {
        parent::__construct($config);
        $this->_preparer = $preparer;
    }

    /**
     * Creates a new release branch
     *
     * @param array $arguments the CLI arguments the command was run with
     */
    public function run($arguments)
    {
        $projectName = $this->_getProjectNameFromArguments($arguments);
        $branchName = $this->_getBranchNameFromArguments($arguments);
        
        $this->_preparer->prepareRelease($projectName, $branchName);
        
        return 'Successfully created branch ' . $branchName . PHP_EOL;
    }

    /**
     * Creates a new release branch
     *
     * @param array $arguments the CLI arguments the command was run with
     * @return string The branch name
     */
    private function _getBranchNameFromArguments($arguments)
    {
        if (1==count($arguments)) {
            throw new Releasr_Exception_CliArgs('No branch name specified');
        }
        return $arguments[1];
    }

    /**
     * Gets a usage message string
     *
     * @return string The usage message for this command
     */
     public function getUsageMessage()
     {
         $usage = 'releasr prepare [projectname] [branchname]';
         return $usage;
     }

      /**
       * Gets the help message for this command
       */ 
      public function getHelpMessage()
      {
          return 'Prepares a new release, using the provided branch name';
      }
}