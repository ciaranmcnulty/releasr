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
     * @var Releasr_Repo_UrlResolver Configuration of how the target repository is set up
     */
    protected $_urlResolver;

    /**
     * @param Releasr_Repo_UrlResolver $urlResolver The config of the repository
     */
    public function __construct($urlResolver)
    {
        $this->_urlResolver = $urlResolver;
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