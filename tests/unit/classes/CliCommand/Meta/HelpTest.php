<?php

/**
 * CLI command that outputs a help message for each command
 * @package Releasr
 */
class Releasr_CliCommand_Meta_HelpTest extends PHPUnit_Framework_Testcase
{
    /**
     * @var Releasr_CliCommand_Help
     */
    private $_command;

    /**
     * @var Releasr_CliCommand_Interface
     */
    private $_mockCommand;

    /**
     * @var Releasr_CliCommand_DocumentedInterface
     */
    private $_mockDocumentedCommand;

    public function setUp()
    {
        $this->_mockCommand = $this->getMock('Releasr_CliCommand_Interface');
        $this->_mockDocumentedCommand = $this->getMock('Releasr_CliCommand_DocumentedInterface');
        $commands = array(
            'undocumentedCommand'=>$this->_mockCommand,
            'documentedCommand' => $this->_mockDocumentedCommand
        );
        $this->_command = new Releasr_CliCommand_Meta_Help($commands);
    }

    /**
     * @expectedException Releasr_Exception_CliArgs
     */
    public function testHelpThrowsAnExceptionIfNoCommandIsSpecified()
    {
        $arguments = array();
        $this->_command->run($arguments);
    }

    /**
     * @expectedException Releasr_Exception_CliArgs
     */
    public function testHelpOutputsExceptionWhenCommandIsNotRecognised()
    {
        $arguments = array('badcommand');
        $this->_command->run($arguments);
    }

    public function testHelpGetsUsageInformationForSpecifiedCommand()
    {
        $arguments = array('undocumentedCommand');
        
        $this->_mockCommand->expects($this->once())
            ->method('getUsageMessage');
        
        $output = $this->_command->run($arguments);
    }

    public function testHelpOutputsUsageInformationForSpecifiedCommand()
    {
        $arguments = array('undocumentedCommand');

        $this->_mockCommand->expects($this->any())
            ->method('getUsageMessage')
            ->will($this->returnValue('%USAGE%'));

        $output = $this->_command->run($arguments);
        
        $this->assertContains('Usage: %USAGE%', $output);
    }
    
    public function testHelpGetsHelpMessageForDocumentedCommand()
    {
        $arguments = array('documentedCommand');

        $this->_mockDocumentedCommand->expects($this->once())
            ->method('getHelpMessage');

        $this->_command->run($arguments);
    }

    public function testHelpOutputsHelpMessageForDocumentedCommand()
    {
        $arguments = array('documentedCommand');

        $this->_mockDocumentedCommand->expects($this->any())
            ->method('getHelpMessage')
            ->will($this->returnValue('%HELP%'));

        $output = $this->_command->run($arguments);
        
        $this->assertContains('%HELP%', $output);
    }
}