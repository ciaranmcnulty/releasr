<?php

/**
* Class representing the coniiguration of a particular repository
*
* @package Releasr
*/
class Releasr_Repo_UrlResolver
{
    /**
     * @var array the Repository config
     */
    private $_config;

    /**
     * @param Releasr_Config $config The application config
     */ 
    public function __construct($config)
    {
        $this->_config = $config;
    }

    /**
     * Gets a URL where the branches of the project are kept
     *
     * @param string The name of the project
     * @return string The URL for project branches
     */
    public function getBranchUrlForProject($projectName)
    {
        return $this->_getUrlFromPattern($projectName, 'releases_url');
    }

    /**
     * Gets a URL where the trunk for the project exists
     *
     * @param string The name of the project
     * @return string The URL for the project's trunk
     */
    public function getTrunkUrlForProject($projectName)
    {
        return $this->_getUrlFromPattern($projectName, 'trunk_url');
    }

    /**
     * Interpolates values into a URL specified in the config
     *
     * @param string The name of the project
     * @param string The config key to use for the url type requested
     * @return string Completed URL
     */
    private function _getUrlFromPattern($projectName, $configKey)
    {
        $urlScheme = $this->_config->getRequiredOption($configKey);
        return str_replace('%PROJECT%', $projectName, $urlScheme);
    }

}