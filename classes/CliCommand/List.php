<?php

/**
 * Command that formats a list of the releases on a particular branch
 */
class Releasr_CliCommand_List implements Releasr_CliCommand_Interface
{
    /**
     * Gets the list for the current project and formats it for CLI
     *
     * @param array Arguments for this specific command
     */
    public function run($arguments)
    {
        if (0==count($arguments)) {
            throw new Releasr_Exception_CliArgs('No project name specified');
        }
    }
}