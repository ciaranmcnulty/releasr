<?php

/**
* Interface for a command that has been documented and can return a help message
*
* @package Releasr
*/
interface Releasr_CliCommand_DocumentedInterface extends Releasr_CliCommand_Interface
{
     /**
      * Gets a help documentation string
      *
      * @return string The documentation message for this command
      */
     public function getHelpMessage();
}