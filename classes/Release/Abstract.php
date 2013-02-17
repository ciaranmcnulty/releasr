<?php

/**
* Class containing a few util functions
*
* @todo Move SVN functionality into a subclass
* @package Releasr
*/
abstract class Releasr_Release_Abstract
{

    /**
     * @var Releasr_Repo_Config Configuration of how the target repository is set up
     */
    protected $_repoConfig;

    /**
     * @param Releasr_Repo_Config $repoConfig The config of the repository
     */
    public function __construct($repoConfig)
    {
        $this->_repoConfig = $repoConfig;
    }  

    /**
     * Does an actual shell command
     *
     * @param string $command The command to run
     * @return string The output of the command
     */
    protected function _doShellCommand($command)
    {
        return shell_exec($command);
    }
}