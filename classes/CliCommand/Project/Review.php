<?php

/**
 * Command that shows activity on trunk since the current release branch was created
 *
 * @package Releasr
 */
class Releasr_CliCommand_Project_Review extends Releasr_CliCommand_Project_Abstract 
{
    /**
     * @var Releasr_Release_Reviewer
     */
    private $_reviewer;

    /**
     * @param Releasr_Config The application config
     * @var Releasr_Release_Reviewer The reviewer to use to talk to the repo
     */
    public function __construct($config, $reviewer) 
    {
        parent::__construct($config);
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
             $message .= $change->author . ' -> ' . trim($change->comment) . PHP_EOL;
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

    /**
     * Gets a usage message string
     *
     * @return string The usage message for this command
     */
     public function getUsageMessage()
     {
         $usage = 'releasr review [projectname]';
         return $usage;
     }

     /**
      * Gets the help message for this command
      */ 
     public function getHelpMessage()
     {
         return 'Reviews all changes on trunk since the last branch was created';
     }
}
