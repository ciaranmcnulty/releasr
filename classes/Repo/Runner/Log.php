<?php

/**
 * Class that does SVN log
 *
 * @package Releasr
 */
class Releasr_Repo_Runner_Log extends Releasr_Repo_Runner_Abstract
{
    /**
     * Does an SVN log on a particular URL
     *
     * @var string $url The URL to log
     * @var boolean $stopOnCopy Whether to stop logging at the last copy point
     * @var integer $startRevision A revision number to start at
     * @return string output from the command
     */
    public function run($url, $stopOnCopy=FALSE, $startRevision=FALSE)
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
}