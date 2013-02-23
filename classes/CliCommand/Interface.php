<?php

/**
* Interface for runnable commands
*
* @package Releasr
*/
interface Releasr_CliCommand_Interface
{
    /**
     * Invokes the command
     *
     * @param array $arguments Command line arguments for the relevant command
     */ 
    public function run($arguments);
    
    /**
     * Gets a usage message string
     *
     * @return string The usage message for this command
     */
    public function getUsageMessage();
}