<?php

/**
 * CLI command that outputs a list of releases on a particular branch
 *
 * @package Releasr
 */
class Releasr_CliCommand_Project_ReviewTest extends PHPUnit_Framework_Testcase
{
    /**
    * @var Releasr_CliCommand_Project_Review
    */
    private $_command;
    
    /**
     * @var Releasr_Controller_Reviewer The object used to query the repository
     */
    private $_releaseReviewer;
    
    /**
    * @var array Example valid arguments for tests that want to pass the checks
    */
    private $_validArguments;
    
    public function setUp()
    {
        $this->_validArguments = array('myproject');

        $config = $this->getMock('Releasr_Config', array(), array(), '', FALSE);
        $this->_releaseReviewer = $this->getMock('Releasr_Controller_Reviewer', array(), array(), '', FALSE);
        $this->_command = new Releasr_CliCommand_Project_Review($config, $this->_releaseReviewer);
    }
    
    /**
     * @expectedException Releasr_Exception_CliArgs
     */
    public function testReviewThrowsAnExceptionIfNoProjectNameIsSpecified()
    {
        $arguments = array();    
        
        $this->_command->run($arguments);
    }
    
    public function testReviewCallsReleaseReviewerWhenValidProjectNameProvided()
    {
        $this->_releaseReviewer->expects($this->once())
               ->method('reviewRelease')
               ->with($this->equalTo('myproject'))
               ->will($this->returnValue(array()));

        $this->_command->run($this->_validArguments);
    }
    
    public function testReviewShowsAppropriateMessageIfNoChangesFound()
    {
        $this->_releaseReviewer->expects($this->any())
             ->method('reviewRelease')
             ->will($this->returnValue(array()));
        
        $output = $this->_command->run($this->_validArguments);

        $this->assertContains('No changes found',  $output);
    }

    public function testReviewShowsAppropriateMessageIfOneChangeFound()
    {
        $change = $this->getMock('Releasr_Repo_Change');
        
        $this->_releaseReviewer->expects($this->any())
             ->method('reviewRelease')
             ->will($this->returnValue(array($change)));

        $output = $this->_command->run($this->_validArguments);

        $this->assertContains('1 change found',  $output);
    }
    
    public function testReviewOutputsListOfTrunkChanges()
    {
        $change1 = $this->getMock('Releasr_Repo_Change');
        $change1->author = 'user1';
        $change1->comment = 'comment1';
        
        $change2 = $this->getMock('Releasr_Repo_Change');
        $change2->author = 'user2';
        $change2->comment = 'comment2';

        $this->_releaseReviewer->expects($this->any())
             ->method('reviewRelease')
             ->will($this->returnValue(array($change1, $change2)));

        $output = $this->_command->run($this->_validArguments);

        $this->assertContains('user1 -> comment1'.PHP_EOL,  $output);
        $this->assertContains('user2 -> comment2'.PHP_EOL,  $output);
    }
    
}