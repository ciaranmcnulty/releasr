<?php

/**
 * Class that does SVN list
 *
 * @package Releasr
 */
class Releasr_Repo_Runner_List extends Releasr_Repo_Runner_Abstract
{
    /**
     * Does an SVN list (can't call this method list() because of PHP builtin function)
     *
     * @param string $url The URL to do the list on
     * @return string output from the command
     */ 
    public function run($url)
    {
        $response = $this->_doShellCommand('svn list --xml ' . escapeshellarg($url));
        return $this->_parseListingXmlIntoReleaseObjects($response, $url);
    }

    /**
     * Builds Release objects based on the response from the repository
     *
     * @param string $xmlResponse Xml svn list response from the repository
     * @param string $relseasesUrl The base URL for branches in this repo
     * @return array Releasr_Repo_Release objects
     */
    private function _parseListingXmlIntoReleaseObjects($xmlResponse, $releasesUrl)
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
        return $releases;
    }
}