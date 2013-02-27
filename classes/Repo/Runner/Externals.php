<?php

/**
 * Class that does SVN externals property query
 *
 * @package Releasr
 */
class Releasr_Repo_Runner_Externals extends Releasr_Repo_Runner_Abstract
{
    /**
     * Gets the externals that are set in a URL's subfolders
     *
     * @param string $url The URL to get the externals from
     * @return array Releasr_Repo_External The externals of the URL
     */
    public function run($url)
    {
        $response = $this->_doShellCommand('svn propget -R svn:externals ' . escapeshellarg($url) . ' --xml');
        return $this->_buildExternalObjectsFromXmlResponse($response);
    }

    /**
     * Parses the XML from the repository into External objects
     *
     * @param string $xmlResponse The raw result from the repository
     * @return array Releasr_Repo_External objects
     */
    private function _buildExternalObjectsFromXmlResponse($xmlResponse)
    {
        $result = array();
        if(!$xml = @simplexml_load_string($xmlResponse)) {
            throw new Releasr_Exception_Repo('Cannot parse response from repository');
        }
        
        foreach ($xml->target as $target) {
            $external = new Releasr_Repo_External;
            $external->path = (string) $target->attributes()->path;
            $external->property = (string) $target->property;
            $array[] = $external;
        }

        return $array;
    }
}