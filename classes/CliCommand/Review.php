<?php

/**
 * Command that shows activity on trunk since the current release branch was created
 *
 * @package Releasr
 */
class Releasr_CliCommand_Review extends Releasr_CliCommand_Abstract 
{
    /**
     * @var Releasr_Release_Reviewer
     */
    private $_reviewer;
    
    /**
     * @var Releasr_Release_Reviewer The reviewer to use to talk to the repo
     */
    public function __construct($reviewer) 
    {
        $this->_reviewer = $reviewer;
    }

    /**
     * Gets the report of the activity on trunk since branching and formats it for display
     *
     * @param array Arguments for this specific command
     */
    public function run($arguments) 
    {
         $projectName = $this->_getProjectNameFromArguments($arguments);
         $changes = $this->_reviewer->reviewRelease($projectName);
         
         $message = $this->_generateNumberOfChangesMessage($changes, $projectName);
         
         foreach ($changes as $change) {
             $message .= $change->user . ' -> ' . $change->comment . PHP_EOL;
         }
         
         return $message;
    }

    /**
     * Generates a sensible message about how many changes there are
     * 
     * @param array $changes The changes
     * @param string $projectName The name of the project
     * @return string Something like '8 releases found for MyProject'
     */
    private function _generateNumberOfChangesMessage($changes, $projectName)
    { 
        $message = $this->_generateNumberOfItemsMessage(count($changes), 'change');
        $message .= ' found';
        if(count($changes)>0) { $message .= ':'; }
        return $message . PHP_EOL;
    }
}