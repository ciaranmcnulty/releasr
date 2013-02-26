<?php

/**
 * Class that does SVN commands
 *
 * @package Releasr
 */
class Releasr_Repo_Runner
{
    /**
     * Does an SVN copy
     * 
     * @param string $source The URL to copy from
     * @param string $destination The URL to copy to
     * @param string $message The message to use in the commit
     * @return string Output from the server
     */
    public function copy($source, $destination, $message)
    {
        $command =  'svn copy ' . escapeshellarg($source)  . ' ' . escapeshellarg($destination) . ' -m ' . escapeshellarg($message);
        $response = $this->_doShellCommand($command);

        if (FALSE === strpos($response, 'Committed revision')) {
            throw new Releasr_Exception_Repo('Could not parse response from repository.');
        }

        return $response;
    }

    /**
     * Does an SVN list (can't call this method list() because of PHP builtin function)
     *
     * @param string $url The URL to do the list on
     * @return string output from the command
     */ 
    public function ls($url)
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

    /**
     * Does an SVN log on a particular URL
     *
     * @var string $url The URL to log
     * @var boolean $stopOnCopy Whether to stop logging at the last copy point
     * @var integer $startRevision A revision number to start at
     * @return string output from the command
     */
    public function log($url, $stopOnCopy=FALSE, $startRevision=FALSE)
    {
        $command = 'svn log --xml ' . escapeshellarg($url);
        if ($stopOnCopy) {
            $command .= ' --stop-on-copy';
        }
        if ($startRevision) {
            $command .= (' -r' . $startRevision . ':HEAD');
        }
        $response = $this->_doShellCommand($command);
        return $this->_buildChangeObjectsFromXmlResponse($response);
    }

    /**
     * Parses the XML from the repository into Change objects
     *
     * @param string $xmlResponse The raw result from the repository
     * @return array Releasr_Repo_Change objects
     */
    private function _buildChangeObjectsFromXmlResponse($xmlResponse)
    {
        if (!$response = @simplexml_load_string($xmlResponse)) {
            throw new Releasr_Exception_Repo('Cannot parse response from repository');
        }

        $changes = array();
        foreach ($response as $entry) {
            $change = new Releasr_Repo_Change();
            $change->author = (string) $entry->author;
            $change->comment = (string) $entry->msg;
            $change->revision = (integer) $entry->attributes()->revision;
            $changes[] = $change;
        }

        return $changes;
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