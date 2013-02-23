<?php

/**
* Exception thrown when bad CLI arguments are provided
*
* @package Releasr
*/
class Releasr_Exception_CliArgs extends Exception
{
    /**
     * @var Releasr_CliCommand_Interface
     */
    protected $_command;
    
    /**
     * @param string $message Error message
     * @param integer $code Error code
     * @param Excepton $previous Previous exception
     * @param Releasr_CliCommand_Interface $command The command executing at the time
     */
    public function __construct($message='', $code=0, Exception $previous=NULL, $command=NULL)
    {
        parent::__construct($message, $code, $previous);
        $this->_command = $command;
    }

    /** 
     * Gets the usage message to display to the user
     *
     * @return string The usage message
     */
    public function getUsageMessage()
    {
        if ($this->_command) {
           return $this->_command->getUsageMessage(); 
        }
        return NULL;
    }
}