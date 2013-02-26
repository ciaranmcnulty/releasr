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

        $this->_svnCopy($trunkUrl, $branchUrl, 'Creating release branch');
    }

    /**
     * Does an SVN copy between two URLs
     *
     * @param string $source The source URL
     * @param string $destination The destination URL
     * @param string The commit message to attach
     */
    private function _svnCopy($source, $destination, $message)
    {
        $command =  'svn copy ' . escapeshellarg($source)  . ' ' . escapeshellarg($destination) . ' -m ' . escapeshellarg($message);
        $response = $this->_doShellCommand($command);
        if (FALSE === strpos($response, 'Committed revision')) {
            throw new Releasr_Exception_Repo('Could not parse response from repository.');
        }        
    }
}