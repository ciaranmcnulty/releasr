<?php

/**
* Class responsible for reviewing a release and returning trunk changes
*
* @package Releasr
*/
class Releasr_Release_Reviewer extends Releasr_Release_Abstract
{
    /**
     * @var Releasr_Release_Lister Used to work out which release is the most recent
     */
    private $_lister;

    /**
     * @param array $repoConfig The config of the repository
     * @param Releasr_Release_Lister $lister The object to use to list releases
     */
    public function __construct($repoConfig, $lister)
    {
        parent::__construct($repoConfig);
        $this->_lister = $lister;
    }

    /**
     * Gets the list of changes on trunk since the last branch was created
     *
     * @param string $projectname The name of the project
     * @return array List of Releasr_Repo_Change representing changes since the last branch was created
     */
    public function reviewRelease($projectName)
    {
        
        $release = $this->_lister->getMostRecentRelease($projectName);   
        $revision = $this->_getRevisionWhenReleaseBranchWasCreated($release);
        return $this->_getRecentChangeObjectsFromTrunk($projectName, $revision);
    }

    /**
     * Works out what the revision number was when a branch was created
     *
     * @param Releasr_Repo_Release The release in question
     * @return integer The release number that branch was created
     */
    private function _getRevisionWhenReleaseBranchWasCreated($release)
    {
        $responseXml = $this->_doShellCommand('svn log --xml --stop-on-copy ' . $release->url);

        if (!$response = @simplexml_load_string($responseXml)) {
            throw new Releasr_Exception_Repo('Cannot read response from repository');
        }

        $lastEntry = $response->xpath('(//logentry)[last()]');
        return (integer) $lastEntry[0]->attributes()->revision;
    }

    /**
     * Finds any changes that have happened on trunk since the specified revision
     *
     * @param string $projectName The name of the current project
     * @param integer $revision The revision number
     * @return array Releasr_Repo_Change objects
     */
    private function _getRecentChangeObjectsFromTrunk($projectName, $revision)
    {
        $xmlResponse = $this->_getRecentChangeDataFromTrunk($projectName, $revision);
        return $this->_buildChangeObjectsFromXmlResponse($xmlResponse);
    }

    /**
     * Gets the log of any changes on trunk since the specified revision
     *
     * @param string $projectName The name of the current project
     * @param integer $revision The revision to get the list since
     * @return SimpleXmlIterator
     */
    private function _getRecentChangeDataFromTrunk($projectName, $revision)
    {
        $trunkUrl = $this->_repoConfig->getTrunkUrlForProject($projectName);
        $xmlResponse = $this->_doShellCommand('svn log --xml -r' . $revision . ':HEAD ' . $trunkUrl);
        return $xmlResponse;
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
            $changes[] = $change;
        }

        return $changes;
    }

}