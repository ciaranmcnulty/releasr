<?php

/**
* Class responsible for listing the existing releases in a project
*
* @package Releasr
*/
class Releasr_Release_Lister extends Releasr_Release_Abstract
{

    /**
     * Gets the most recent release for a particular project
     *
     * @param string $projectName
     * @return Releasr_Repo_Release
     */
    public function getMostRecentRelease($projectName)
    {
        $releases = $this->listReleases($projectName);
        return end($releases);
    }

    /**
     * Gets the list of releases for the named project
     *
     * @param string $projectName
     * @return array Releasr_Repo_Release objects for the project
     */
    public function listReleases($projectName)
    {
        $releasesUrl = $this->_urlResolver->getBranchUrlForProject($projectName);
        $xmlResponse = $this->_doShellCommand('svn list --xml '.$releasesUrl);
        return $this->_parseXmlIntoReleaseObjects($xmlResponse, $releasesUrl);
    }

    /**
     * Builds Release objects based on the response from the repository
     *
     * @param string $xmlResponse Xml svn list response from the repository
     * @param string $relseasesUrl The base URL for branches in this repo
     * @return array Releasr_Repo_Release objects
     */
    private function _parseXmlIntoReleaseObjects($xmlResponse, $releasesUrl)
    {        
        if (!$xml = @simplexml_load_string($xmlResponse)) {
            throw new Releasr_Exception_Repo('Could not parse response from repository');
        }

        $releases = array();
        foreach ($xml->list->entry as $entry) {
            $release = new Releasr_Repo_Release;
            $release->name = (string)$entry->name;
            $release->url = $releasesUrl . '/' . (string)$entry->name;
            $release->date = new DateTime((string)$entry->commit->date);
            $releases[] = $release;
        }
        usort($releases, array($this, '_sortByDate'));
        return $releases;
    }

    /**
     * @param DateTime $a
     * @param DateTime $b
     * @return integer comparison of the two dates
     */
    private function _sortByDate($a, $b)
    {
        return $a->date->format('U') - $b->date->format('U');
    }
}