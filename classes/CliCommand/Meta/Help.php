<?php

/**
 * Command that outputs a help message for the specified command
 *
 * @package Releasr
 */
class Releasr_CliCommand_Meta_Help extends Releasr_CliCommand_Meta_Abstract
{
    
    /**
     * Gets the help message for the command specified
     *
     * @param array Arguments for this specific command
     */
    public function run($arguments)
    {
        $command = $this->_getCommandFromArguments($arguments);
        return 'Usage: ' . $command->getUsageMessage();
    }
    
    /**
     * Gets a usage message string
     *
     * @return string The usage message for this command
     */    
    public function getUsageMessage()
    {
        $usage = 'releasr help [command]' . PHP_EOL;
        $usage .= 'Available commands: "' . join('", "', array_keys($this->_commands)) . '"';
        return $usage;
    }
}