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
     * @param array $urlResolver The config of the repository
     * @param Releasr_Repo_Runner $svnRunner The object to use to execute SVN commands
     * @param Releasr_Release_Lister $lister The object to use to list releases
     */
    public function __construct($urlResolver, $svnRunner, $lister)
    {
        parent::__construct($urlResolver, $svnRunner);
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
        return $this->_getChangesOnTrunkSinceRevision($projectName, $revision);
    }

    /**
     * Works out what the revision number was when a branch was created
     *
     * @param Releasr_Repo_Release The release in question
     * @return integer The release number that branch was created
     */
    private function _getRevisionWhenReleaseBranchWasCreated($release)
    {
        $changes = $this->_svnRunner->log($release->url, TRUE);
        return end($changes);
    }

    /**
     * Finds any changes that have happened on trunk since the specified revision
     *
     * @param string $projectName The name of the current project
     * @param Repo_Change $change The revision number
     * @return array Releasr_Repo_Change objects
     */
    private function _getChangesOnTrunkSinceRevision($projectName, $change)
    {
        $trunkUrl = $this->_urlResolver->getTrunkUrlForProject($projectName);
        return $this->_svnRunner->log($trunkUrl, FALSE, $change->revision);
    }

}
