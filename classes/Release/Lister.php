<?php

/**
* Class responsible for listing the existing releases in a project
*
* @package Releasr
*/
class Releasr_Release_Lister extends Releasr_Release_Abstract
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
     * @param DateTime $a
     * @param DateTime $b
     * @return integer comparison of the two dates
     */
    private function _sortByDate($a, $b)
    {
        return $a->date->format('U') - $b->date->format('U');
    }
}