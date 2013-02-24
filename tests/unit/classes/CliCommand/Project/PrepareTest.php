<?php

/**
 * CLI command that creates a new release
 *
 * @package Releasr
 */
class Releasr_CliCommand_Project_PrepareTest extends PHPUnit_Framework_Testcase
{
    /**
    * @var Releasr_CliCommand_Prepare
    */
    private $_command;
    
    /**
     * @var Releasr_Release_Preparer The object used to query the repository
     */
    private $_releaseReviewer;
    
    /**
    * @var array Example valid arguments for tests that want to pass the checks
    */
    private $_validArguments;
    
    public function setUp()
    {
        $this->_validArguments = array('myproject', 'mybranch');

        $this->_releasePreparer = $this->getMock('Releasr_Release_Preparer', array(), array(), '', FALSE);
        $this->_command = new Releasr_CliCommand_Project_Prepare($this->_releasePreparer);
    }

    /**
    * @expectedException Releasr_Exception_CliArgs
    */
    public function testPrepareThrowsAnExceptionIfNoProjectNameIsSpecified()
    {
        $arguments = array();    

        $this->_command->run($arguments);
    }

    /**
    * @expectedException Releasr_Exception_CliArgs
    */
    public function testPrepareThrowsAnExceptionIfNoBranchNameIsSpecified()
    {
        $arguments = array('myproject');    

        $this->_command->run($arguments);
    }

    public function testPrepareRunsCorrectMethodOnPreparer()
    {
        $this->_releasePreparer->expects($this->once())
            ->method('prepareRelease')
            ->with(
                $this->equalTo('myproject'),
                $this->equalTo('mybranch')
            );

        $this->_command->run($this->_validArguments);
    }

    public function testPrepareOutputsSuccessMessage()
    {
        $output = $this->_command->run($this->_validArguments);
        
        $this->assertContains('Successfully created branch', $output);
    }
}