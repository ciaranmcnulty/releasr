<?php

/**
* Class representing the coniiguration of a particular repository
*
* @package Releasr
*/
class Releasr_Repo_Config
{
    /**
     * @var array the Repository config
     */
    private $_config;

    /**
     * @param string $configFile The file specifying the repo setup
     */ 
    public function __construct($configFile)
    {
        $this->_config = $this->_doParseIniFile($configFile);
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
        if (!array_key_exists($configKey, $this->_config)) {
            throw new Releasr_Exception_Config('Missing required config option "releases_url"');
        }

        $urlScheme = $this->_config[$configKey];
        return str_replace('%PROJECT%', $projectName, $urlScheme);
    }

    /**
     * Reads the config file from disk 
     *
     * @param string $configFile The file specifying the repo setup
     * @return array The config in the .ini file
     */
    protected function _doParseIniFile($configFile)
    {
        return parse_ini_file($configFile);
    }
}