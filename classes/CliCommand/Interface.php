<?php

/**
* Interface for runnable commands. By default they must be runnable and have some usage info
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