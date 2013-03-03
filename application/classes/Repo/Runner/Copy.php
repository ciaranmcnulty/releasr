<?php

/**
 * Class that does SVN copy
 *
 * @package Releasr
 */
class Releasr_Repo_Runner_Copy extends Releasr_Repo_Runner_Abstract
{
    /**
     * Does an SVN copy
     * 
     * @param string $source The URL to copy from
     * @param string $destination The URL to copy to
     * @param string $message The message to use in the commit
     * @return string Output from the server
     */
     public function run($source, $destination, $message)
     {
         $command =  'svn copy ' . escapeshellarg($source)  . ' ' . escapeshellarg($destination) . ' -m ' . escapeshellarg($message);
         $response = $this->_doShellCommand($command);

         if (FALSE === strpos($response, 'Committed revision')) {
             throw new Releasr_Exception_Repo('Could not parse response from repository.');
         }

         return $response;
     }
}