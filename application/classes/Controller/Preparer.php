<?php

/**
* Class responsible for making new releases in a project
*
* @package Releasr
*/
class Releasr_Controller_Preparer extends Releasr_Controller_Abstract
{
    /**
     * @var Releasr_Controller_Lister Used to work out which release is the most recent
     */
    private $_lister;

    /**
     * @param array $urlResolver The config of the repository
     * @param Releasr_Repo_Runner $svnRunner The object to use to execute SVN commands
     * @param Releasr_Controller_Lister $lister The object to use to list releases
     */
    public function __construct($urlResolver, $svnRunner, $lister)
    {
        parent::__construct($urlResolver, $svnRunner);
        $this->_lister = $lister;
    }
    
    /**
     * Creates a new release branch and returns any externals
     *
     * @param string $projectName The name of the current project
     * @param string $branchName The name of the new branch to be created
     * @return array Releasr_Repo_Externals object report on the new URL
     */
    public function prepareRelease($projectName, $branchName)
    {
        $trunkUrl = $this->_urlResolver->getTrunkUrlForProject($projectName);
        $branchUrl = $this->_urlResolver->getBranchUrlForProject($projectName) . '/' . $branchName;

        $this->_checkBranchDoesNotExist($projectName, $branchName);
        $this->_createReleaseBranch($trunkUrl, $branchUrl);

        return $this->_getUnversionedExternalsFromBranch($branchUrl);
    }

    /**
     * Determines whether a branch already exists and if so throws an appropriate Exception
     *
     * @param string $projectName
     * @param string $branchName
     */
    private function _checkBranchDoesNotExist($projectName, $branchName)
    {    
        if ($this->_lister->getRelease($projectName, $branchName)) {
            throw new Releasr_Exception_BranchExists('Cannot create release ' . $branchName . ' because it already exists');
        }
    }

    /**
     * Creates a new release branch
     * 
     * @param string $trunkUrl The trunk URL
     * @param string $branchUrl the branch URL
     */
    private function _createReleaseBranch($trunkUrl, $branchUrl)
    {    
        $this->_svnRunner->copy($trunkUrl, $branchUrl, 'Creating release branch');
    }

    /**
     * Gets any externals on the branch that are not explicitly versioned
     * 
     * @param string $branchUrl the branch URL
     */
    private function _getUnversionedExternalsFromBranch($branchUrl)
    {
        $externals = $this->_svnRunner->externals($branchUrl);
        
        $unversioned = array();
        foreach ($externals as $external) {
            if($external->hasUnversionedExternals()){
                $unversioned[] = $external;
            }
        }
        return $unversioned;
    }
}