<?php

/**
 * Abstract class for CliCommands that contains common functionality
 *
 * @package Releasr
 */
abstract class Releasr_CliCommand_Abstract implements Releasr_CliCommand_Interface
{

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
        return $arguments[0];
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