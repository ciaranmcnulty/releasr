<?php

/**
* Abstract class for commands that deal with other commands (e.g. runner, help)
* 
* @package Releasr
*/
abstract class Releasr_CliCommand_Meta_Abstract implements Releasr_CliCommand_Interface
{
    
    /** 
     * The array of commands that this command knows about
     *
     * e.g. array('name' => $command) where $command instance of Releasr_CliCommand_Interface
     */
    protected $_commands;

    /**
     * @param array $commands The configured commands in the system
     */
    public function __construct($commands)
    {
        $this->_commands = $commands;
    }
    
    /**
     * Gets a runnable command based on the arguments and the commands specified in config
     *
     * @param array The arguments provided to the runner
     * @return Releasr_CliCommand_Interface An invokable command
     */
    protected function _getCommandFromArguments($arguments)
    {
        $commandName = $this->_getCommandNameFromArguments($arguments);
        return $this->_getCommandObjFromConfig($commandName);
    }

    /**
     * Works out which command name is provided in the arguments
     *
     * @param array The arguments provided to the runner
     * @return string The name of the command to invoke
     */
    protected function _getCommandNameFromArguments($arguments)
    {
        if (0 == count($arguments)) {
            throw new Releasr_Exception_CliArgs('No command name provided', 0, NULL, $this);
        }
        return $arguments[0];
    }

    /**
     * Retrieves the named command from the configured list
     *
     * @param string $commandName The name of the command to be fetched
     * @return Releasr_CliCommand_Interface An invokable command
     */
    protected function _getCommandObjFromConfig($commandName)
    {
        if (!array_key_exists($commandName, $this->_commands)) {
            throw new Releasr_Exception_CliArgs('Provided command "' . $commandName . '" not recognised', 0, NULL, $this);
        }
        return $this->_commands[$commandName];
    }
}