<?php

/**
* Class representing the application's configuration
*
* @package Releasr
*/
class Releasr_Config
{
    /**
     * @var array The parsed config
     */
    private $_config;
    
    /**
     * @param array $configs The list of file locations to look in for a valid config
     */
    public function __construct($configs)
    {
        foreach ($configs as $configFile) {
            if (FALSE !== $config = $this->_doParseIniFile($configFile)) {
                $this->_config = $config;
                break;
            }
        }

        if (!is_array($this->_config)) {
            $errorMessage = 'Could not find parsable config file in "' . join('", "', $configs) . '"';
            throw new Releasr_Exception_Config($errorMessage);
        }
    }

    /**
     * Gets the option specified in the configuration
     *
     * @param string $key The option key to retrieve
     * @return mixed
     */
    public function getOption($key)
    {
        try{
            return $this->getRequiredOption($key);
        }
        catch (Releasr_Exception_Config $e) {
            return NULL;
        }
    }

    /**
     * Gets the option specified in the configuration and throws an exception if the value is not present
     *
     * @param string $key The option key to retrieve
     * @return mixed
     */
    public function getRequiredOption($key)
    {
        if (!array_key_exists($key, $this->_config)) {
            throw new Releasr_Exception_Config('Missing required config option "' . $key . '"');
        }
        return $this->_config[$key];
    }

    /**
     * Reads the config file from disk 
     *
     * @param string $configFile The file specifying the repo setup
     * @return array The config in the .ini file
     */
    protected function _doParseIniFile($configFile)
    {
        return @parse_ini_file($configFile);
    }

    /**
     * Gets the list of configured projects
     *
     * @return array The list of projects
     */
    public function getProjects()
    {
        if (!$projects = $this->getOption('projects')) {
            return NULL;
        }
        
        $projects = split(',', $projects);
        return array_map('trim', $projects);
    }
}