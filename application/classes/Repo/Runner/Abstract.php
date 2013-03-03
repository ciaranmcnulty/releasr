<?php

/**
 * Abstract class for classes that do SVN shell commands
 *
 * @package Releasr
 */
abstract class Releasr_Repo_Runner_Abstract
{
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