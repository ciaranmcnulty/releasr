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
        $message = '';
        if ($command instanceof Releasr_CliCommand_DocumentedInterface) {
            $message .= ($command->getHelpMessage() . PHP_EOL);
        }
        $message .=  ('Usage: ' . $command->getUsageMessage());
        return $message;
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
        foreach ($this->_commands as $key => $command) {
            if ($command instanceof Releasr_CliCommand_DocumentedInterface) {
                $usage .= PHP_EOL;
                $usage .= (PHP_EOL . $key . ': ' . PHP_EOL);
                $usage .= ("\t" . $command->getHelpMessage());
            }
        }
        return $usage;
    }
}