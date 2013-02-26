<?php

/**
 * CLI command that outputs a list of releases on a particular branch
 * @package Releasr
 */
class Releasr_CliCommand_Project_ListTest extends PHPUnit_Framework_Testcase
{
    
    /**
     * @var Releasr_CliCommand_Project_List The list command under test
     */
    private $_command;
    
    /**
     * @var Releasr Release_Lister The object used to query the repository
     */
    private $_releaseLister;
    
    /**
    * @var array Example valid arguments for tests that want to pass the checks
    */
    private $_validArguments;
    
    public function setUp()
    {
        $this->_validArguments = array('myproject');

        $config = $this->getMock('Releasr_Config', array(), array(), '', FALSE);
        $this->_releaseLister = $this->getMock('Releasr_Controller_Lister', array(), array(), '', FALSE);
        $this->_command = new Releasr_CliCommand_Project_List($config, $this->_releaseLister);
    }
    
    /**
     * @expectedException Releasr_Exception_CliArgs
     */
    public function testListThrowsAnExceptionIfNoProjectNameIsSpecified()
    {
        $arguments = array();    
        
        $this->_command->run($arguments);
    }
    
    public function testListCallsReleaseListerWhenProjectNameIsSpecified()
    {
        $this->_releaseLister->expects($this->any())
             ->method('listReleases')
             ->will($this->returnValue(array()));
                 
        $this->_releaseLister->expects($this->once())
             ->method('listReleases')
             ->with($this->equalTo('myproject'));
         
        $this->_command->run($this->_validArguments);
    }
    
    public function testListOutputsSomeText()
    {
        $this->_releaseLister->expects($this->any())
             ->method('listReleases')
             ->will($this->returnValue(array()));
                 
        $output = $this->_command->run($this->_validArguments);
        
        $this->assertInternalType('string',  $output);
        $this->assertTrue(strlen($output) > 0);
    }
    
    public function testListShowsAppropriateMessageIfNoReleasesFound()
    {
        $this->_releaseLister->expects($this->any())
             ->method('listReleases')
             ->will($this->returnValue(array()));
        
        $output = $this->_command->run($this->_validArguments);

        $this->assertContains('No releases found',  $output);
    }

    public function testListShowsNumberOfReleasesFound()
    {
        $release = $this->getMock('Releasr_Repo_Release');

        $this->_releaseLister->expects($this->any())
            ->method('listReleases')
            ->will($this->returnValue(array(
                $release, $release, $release
            )));

        $output = $this->_command->run($this->_validArguments);

        $this->assertContains('3 releases found',  $output);
    }

    public function testListProperlyPluralisesWhenThereIsOnlyOneRelease()
    {
        $release = $this->getMock('Releasr_Repo_Release');

        $this->_releaseLister->expects($this->any())
            ->method('listReleases')
            ->will($this->returnValue(array($release)));

        $output = $this->_command->run($this->_validArguments);

        $this->assertContains('1 release found',  $output);
    }
    
    public function testListOutputsNamesOfReleases()
    {    
        $release1 = $this->getMock('Releasr_Repo_Release');
        $release1->name = 'FOO';
        
        $release2 = $this->getMock('Releasr_Repo_Release');
        $release2->name = 'BAR';

        $this->_releaseLister->expects($this->any())
            ->method('listReleases')
            ->will($this->returnValue(array($release1, $release2)));

        $output = $this->_command->run($this->_validArguments);
        
        $this->assertContains('-> FOO'.PHP_EOL, $output);
        $this->assertContains('-> BAR'.PHP_EOL, $output);

    }
}