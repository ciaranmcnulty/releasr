<?php

/**
 * Command that formats a list of the releases on a particular branch
 *
 * @package Releasr
 */
class Releasr_CliCommand_List implements Releasr_CliCommand_Interface
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
     * Assumes the name of the project is the first argument provided
     *
     * @param array $arguments
     */
    protected function _getProjectNameFromArguments($arguments)
    {
        if (0==count($arguments)) {
            throw new Releasr_Exception_CliArgs('No project name specified');
        }
        return $arguments[0];
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
        $message = '';
        
        // No releases, 1 release or X releases
        $message .= 0==count($releases) ? 'No' : count($releases);
        $message .= ' release';
        if(count($releases) != 1) { $message.='s'; }
        
        $message .= ' found for "' . $projectName . '"';
        
        // colon at EOL if there is more to come
        if(count($releases)>0) { $message .= ':'; }
        
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
}