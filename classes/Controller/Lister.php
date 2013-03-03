<?php

/**
* Class responsible for listing the existing releases in a project
*
* @package Releasr
*/
class Releasr_Controller_Lister extends Releasr_Controller_Abstract
{

    /**
     * Gets the most recent release for a particular project
     *
     * @param string $projectName
     * @return Releasr_Repo_Release
     */
    public function getMostRecentRelease($projectName)
    {
        $releases = $this->listReleases($projectName);
        return end($releases);
    }

    /**
     * Gets the list of releases for the named project
     *
     * @param string $projectName
     * @return array Releasr_Repo_Release objects for the project
     */
    public function listReleases($projectName)
    {
        $releasesUrl = $this->_urlResolver->getBranchUrlForProject($projectName);
        $releases = $this->_svnRunner->ls($releasesUrl);
        usort($releases, array($this, '_sortByDate'));
        return $releases;
    }

    /**
     * Gets a particular release by name, if it exists
     *
     * @param string $projectName
     * @param string $branchName
     * @return Releasr_Repo_Release|boolean The release or false if not found
     */
    public function getRelease($projectName, $branchName)
    {
        $releases = $this->listReleases($projectName);
        foreach ($releases as $release) {
            if ($release->name == $branchName) {
                return $release;
            }
        }
        return FALSE;
    }

    /**
     * @param DateTime $a
     * @param DateTime $b
     * @return integer comparison of the two dates
     */
    private function _sortByDate($a, $b)
    {
        return $a->date->format('U') - $b->date->format('U');
    }
}