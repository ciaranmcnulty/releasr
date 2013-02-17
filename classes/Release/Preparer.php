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
     */
    public function prepareRelease($projectName, $branchName)
    {
        $trunkUrl = $this->_repoConfig->getTrunkUrlForProject($projectName);
        $branchUrl = $this->_repoConfig->getBranchUrlForProject($projectName) . '/' . $branchName;

        $this->_doShellCommand('svn copy ' . $trunkUrl . ' ' . $branchUrl . ' -m "Creating release branch"');
    }
}