<?php

/**
* Class responsible for listing the existing releases in a project
*
* @package Releasr
*/
class Releasr_Release_Lister
{
    
    /**
     * Configuration of how the target repository is set up
     */
    private $_repoConfig;
    
    /**
     * @param array $repoConfig Configuration of how to generate URLs to the specified repo
     */
    public function __construct($repoConfig)
    {
        $this->_repoConfig = $repoConfig;
    }
    
    /**
     * Gets the list of releases for the named project
     *
     * @param string $projectName
     * @return array Releasr_Repo_Release objects for the project
     */
    public function listReleases($projectName)
    {
        $releasesUrl = $this->_getReleaseBranchesUrlForProject($projectName);
        $xmlResponse = $this->_doShellCommand('svn list --xml '.$releasesUrl);
        return $this->_parseXmlIntoReleaseObjects($xmlResponse);
    }
    
    /**
     * Works out the release branches URL for a particular project
     *
     * @param string $projectName The name of the project
     * @return string the URL of the release branch
     */
    private function _getReleaseBranchesUrlForProject($projectName)
    {
        if (!array_key_exists('releases_url', $this->_repoConfig)) {
            throw new Releasr_Exception_Config('Missing required config option "releases_url"');
        }
        
        $urlScheme = $this->_repoConfig['releases_url'];
        return str_replace('%PROJECT%', $projectName, $urlScheme);
    }
    
    /**
     * @param string Xml svn list response from the repository
     * @return array Releasr_Repo_Release objects
     */
    private function _parseXmlIntoReleaseObjects($xmlResponse)
    {        
        if (!$xml = @simplexml_load_string($xmlResponse)) {
            throw new Releasr_Exception_Repo('Could not parse response from repository');
        }
        
        $releases = array();
        foreach ($xml->list->entry as $entry) {
            $release = new Releasr_Repo_Release;
            $release->name = (string)$entry->name;
            $releases[] = $release;
        }
        return $releases;
    }
    
    /**
     * Does an actual shell command
     *
     * @param string $command The command to run
     * @return string The output of the command
     */
    protected function _doShellCommand($command)
    {
        return shell_exec($command);
    }
}