<?php

/**
 * Abstract class for CliCommands that contains common functionality
 *
 * @package Releasr
 */
abstract class Releasr_CliCommand_Project_Abstract implements Releasr_CliCommand_DocumentedInterface
{
    /**
     * @var Releasr_Config
     */
    protected $_config;

    /**
     * @param Releasr_Config The application config
     */
    public function __construct($config)
    {
        $this->_config = $config;
    }

    /**
     * Gets the name of the project from the provided arguments
     *
     * @param array $arguments
     */
    protected function _getProjectNameFromArguments($arguments)
    {
        if (0==count($arguments)) {
            throw new Releasr_Exception_CliArgs('No project name specified', 0, NULL, $this);
        }
        $project = $arguments[0];
        if ($projects = $this->_config->getProjects()) {
            if (!in_array($project, $projects)) {
                $errorMessage = 'Invalid project name. Valid projects: "' . join('", "', $projects) . '"';
                throw new Releasr_Exception_CliArgs($errorMessage, 0, NULL, $this);
            }
        }
        return $project;
    }

    /**
     * Generates a sensible message about how many items are in a list there are
     * 
     * @param integer $itemCount The number of items
     * @param array $itemName The stem of the item name e.g. 'item'
     * @return string Something like No items, 1 item or X items
     */
    protected function _generateNumberOfItemsMessage($itemCount, $itemName)
    { 
        $message = 0==$itemCount ? 'No' : $itemCount;
        $message .= ' ' . $itemName;
        if(1 != $itemCount) { $message.='s'; }

        return $message;
    }
}