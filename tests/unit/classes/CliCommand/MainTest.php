<?php

/**
* @package Releasr
* @subpackage Tests
*/
class Releasr_CliCommand_MainTest extends PHPUnit_Framework_Testcase
{
    
    /**
     * @var Releasr_CliCommand_Interface
     */
    private $_mockCommand;
    
    /**
     * The concrete class under test
     *
     * @var Releasr_CliCommand_Main
     */ 
    private $_command;
    
    public function setUp()
    {
        $this->_mockCommand = $this->getMock('Releasr_CliCommand_Interface');
        
        $commandConfig = array(
            'known_command' => $this->_mockCommand
        );
        
        $this->_command = new Releasr_CliCommand_Main($commandConfig);
    }
    
    /**
    * @expectedException Releasr_Exception_CliArgs
    */
    public function testCommandThrowsExceptionWhenNoArgsAreProvided()
    {
        $arguments = array();
        
        $this->_command->run($arguments);
    }
    
    /**
    * @expectedException Releasr_Exception_CliArgs
    */
    public function testCommandThrowsExceptionWhenUnrecognisedNameIsProvided()
    {
        $arguments = array('unknown_command');
        
        $this->_command->run($arguments);
    }
    
    public function testCommandInvokesRunOnRegisteredCommandWhenRecognisedNameIsProvided()
    {
        $arguments = array('known_command');
        $this->_mockCommand->expects($this->once())
            ->method('run');
        
        $this->_command->run($arguments);
    }
    
    public function testCommandInvokesRegisteredCommandWithSubsetOfArguments()
    {
        $arguments = array('known_command', 'a', 'b', 'c');

        $this->_mockCommand->expects($this->any())
            ->method('run')
            ->with($this->equalTo(array('a', 'b', 'c')));

        $this->_command->run($arguments);   
    }
    
    public function testCommandReturnsOutputOfInvokedRegisteredCommand()
    {
        $arguments = array('known_command');

        $this->_mockCommand->expects($this->any())
            ->method('run')
            ->will($this->returnValue('example output'));

        $output = $this->_command->run($arguments);
        
        $this->assertSame('example output', $output);
    }
    
}