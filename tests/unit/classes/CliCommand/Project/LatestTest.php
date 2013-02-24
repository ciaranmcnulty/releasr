<?php

/**
 * CLI command that outputs details of the latest release
 * @package Releasr
 */
class Releasr_CliCommand_Project_LatestTest extends PHPUnit_Framework_Testcase
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
        
        $this->_releaseLister = $this->getMock('Releasr_Release_Lister', array(), array(), '', FALSE);
        $this->_command = new Releasr_CliCommand_Project_Latest($this->_releaseLister);
    }

    public function testRunGetsLatestReleaseForTheProject()
    {
        $this->_releaseLister->expects($this->once())
            ->method('getMostRecentRelease')
            ->with($this->equalTo('myproject'));

        $this->_command->run($this->_validArguments);
    }
    
    public function testRunShowsProperMessageIfThereAreNoReleases()
    {
        $this->_releaseLister->expects($this->any())
            ->method('getMostRecentRelease')
            ->will($this->returnValue(NULL));

        $output = $this->_command->run($this->_validArguments);
        
        $this->assertContains('No releases', $output);
    }
    
    public function testRunOutputsNameOfLatestRelease()
    {
        $release = $this->getMock('Releasr_Repo_Release');
        $release->name = 'NAME';
        $release->url = 'URL';

        $this->_releaseLister->expects($this->any())
            ->method('getMostRecentRelease')
            ->will($this->returnValue($release));

        $output = $this->_command->run($this->_validArguments);

        $this->assertContains('NAME', $output);
        $this->assertContains('URL', $output);
    }
}