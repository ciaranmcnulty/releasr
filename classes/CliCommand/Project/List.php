<?php

/**
 * Command that formats a list of the releases on a particular branch
 *
 * @package Releasr
 */
class Releasr_CliCommand_Project_List extends Releasr_CliCommand_Project_Abstract
{
    /**
     * @var Releasr_Release_Lister
     */
    private $_releaseLister;

    /**
     * @param Releasr_Release_Lister $releaseLister The object that is used to list the releases
     */
    public function __construct($releaseLister)
    {
        $this->_releaseLister = $releaseLister;
    }

    /**
     * Gets the list for the current project and formats it for CLI
     *
     * @param array Arguments for this specific command
     */
    public function run($arguments)
    {
        $projectName = $this->_getProjectNameFromArguments($arguments);
        $releases = $this->_releaseLister->listReleases($projectName);

        $message = $this->_generateNumberOfReleasesMessage($releases, $projectName);
        $message .= $this->_generateListingOfReleasesMessage($releases);

        return $message;
    }
    
    /**
     * Generates a sensible message about how many releases there are
     * 
     * @param array $releases The releases
     * @param string $projectName The name of the project
     * @return string Something like '8 releases found for MyProject'
     */
    private function _generateNumberOfReleasesMessage($releases, $projectName)
    { 
        $message = $this->_generateNumberOfItemsMessage(count($releases), 'release');
        $message .= ' found for "' . $projectName . '"';
        if (count($releases)>0) { $message .= ':'; }
        return $message . PHP_EOL;
    }

    /**
     * Generates a list of release names
     * 
     * @param array $releases The releases
     * @return string Listing of the releases
     */
    private function _generateListingOfReleasesMessage($releases)
    {              
        $message = '';
        foreach ($releases as $release) {
            $message .= '-> ' . $release->name . PHP_EOL;
        }
        return $message;
    }

    /**
     * Gets a usage message string
     *
     * @return string The usage message for this command
     */
     public function getUsageMessage()
     {
         $usage = 'releasr list [projectname]';
         return $usage;
     }

     /**
      * Gets the help message for this command
      */ 
     public function getHelpMessage()
     {
         return 'Lists all of the existing releases that have previously ' . 
             'been created for the current project';
     }
}