<?php

/**
* Class that decides which command to run and then invokes it based on arguments provided
* 
* @package Releasr
*/
class Releasr_CliCommand_Meta_Main extends Releasr_CliCommand_Meta_Abstract
{
    /**
     * Invokes the appropriate command based on the arguments provided
     *
     * @param array $arguments The arguments provided to the CLI, minus the executable name
     * @throws Releasr_Exception_CliArgs
     */
    public function run($arguments)
    {
        $command = $this->_getCommandFromArguments($arguments);
        $commandArguments = array_slice($arguments, 1);
        return $command->run($commandArguments);
    }
    
    /**
     * Gets a usage message string
     *
     * @return string The usage message for this command
     */    
    public function getUsageMessage()
    {
        $usage = 'releasr [command] [options]' . PHP_EOL;
        $usage .= 'Available commands: "' . join('", "', array_keys($this->_commands)) . '"';
        return $usage;
    }
}