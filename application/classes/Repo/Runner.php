<?php

/**
 * Class that does SVN commands
 *
 * @package Releasr
 */
class Releasr_Repo_Runner
{
    /**
     * @var List of subcommands
     */
    private $_commands;
    
    /**
     * @param array List of subcommands
     */
    public function __construct($commands)
    {
        $this->_commands = $commands;
    }

    /**
     * Does an SVN list (can't call this method list() because of PHP builtin function)
     *
     * @param string $url The URL to do the list on
     * @return string output from the command
     */
    public function ls($url)
    {
        return $this->_commands['list']->run($url);
    }

    /**
     * Does an SVN copy
     * 
     * @param string $source The URL to copy from
     * @param string $destination The URL to copy to
     * @param string $message The message to use in the commit
     * @return string Output from the server
     */
    public function copy($source, $destination, $message)
    {
        return $this->_commands['copy']->run($source, $destination, $message);
    }

    /**
     * Does an SVN log on a particular URL
     *
     * @var string $url The URL to log
     * @var boolean $stopOnCopy Whether to stop logging at the last copy point
     * @var integer $startRevision A revision number to start at
     * @return string output from the command
     */
    public function log($url, $stopOnCopy=FALSE, $startRevision=FALSE)
    {
        return $this->_commands['log']->run($url, $stopOnCopy, $startRevision);
    }

    /**
     * Gets the externals that are set in a URL's subfolders
     *
     * @param string $url The URL to get the externals from
     * @return array Releasr_Repo_External The externals of the URL
     */
    public function externals($url)
    {
        return $this->_commands['externals']->run($url);
    }
}