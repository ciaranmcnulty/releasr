<?php

/**
* Class that decides which command to run and then invokes it based on arguments provided
* 
* @package Releasr
*/
class Releasr_CliCommand_Main implements Releasr_CliCommand_Interface
{
    /** 
     * The array of commands that the runner knows about
     *
     * e.g. array('name' => $command) where $command instance of Releasr_CliCommand_Interface
     */
    private $_commands;

    /**
     * @param array $commands The configured commands in the system
     */
    public function __construct($commands)
    {
        $this->_commands = $commands;
    }

    /**
     * Invokes the appropriate command based on the arguments provided
     *
     * @param array $arguments The arguments provided to the CLI, minus the executable name
     * @throws Releasr_Exception_CliArgs
     */
    public function run($arguments)
    {
        $commandName = $this->_getCommandNameFromArgs($arguments);
        $commandObj = $this->_getCommandObjFromConfig($commandName);
        
        $commandArguments = array_slice($arguments, 1);
        return $commandObj->run($commandArguments);
    }

    /**
     * Works out which command name is provided in the arguments
     *
     * @param array The arguments provided to the runner
     * @return string The name of the command to invoke
     */
    private function _getCommandNameFromArgs($arguments)
    {
        if (0 == count($arguments)) {
            throw new Releasr_Exception_CliArgs('No command name provided');
        }
        return $arguments[0];
    }
    
    /**
     * Retrieves the named command from the configured list
     *
     * @param string $commandName The name of the command to be fetched
     * @return Releasr_CliCommand_Interface An invokable command
     */
    private function _getCommandObjFromConfig($commandName)
    {
        if (!array_key_exists($commandName, $this->_commands)) {
            throw new Releasr_Exception_CliArgs('Provided command ' . $commandName . ' not recognised');
        }
        return $this->_commands[$commandName];
    }
}