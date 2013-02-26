<?php

/**
* Class responsible for making new releases in a project
*
* @package Releasr
*/
class Releasr_Release_Preparer extends Releasr_Release_Abstract
{
    /**
     * Creates a new release branch
     *
     * @param string $projectName The name of the current project
     * @param string $branchName The name of the new branch to be created
     * @param boolean $freezeExternals Whether to try and freeze the external revisions after success
     */
    public function prepareRelease($projectName, $branchName, $freezeExternals=FALSE)
    {
        $trunkUrl = $this->_urlResolver->getTrunkUrlForProject($projectName);
        $branchUrl = $this->_urlResolver->getBranchUrlForProject($projectName) . '/' . $branchName;

        $this->_svnRunner->copy($trunkUrl, $branchUrl, 'Creating release branch');
    }
}