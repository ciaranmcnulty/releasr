<?php

/**
 * CLI command that outputs a list of releases on a particular branch
 */
class Releasr_CliCommand_ListTest extends PHPUnit_Framework_Testcase
{
    
    /**
     * The list command under test
     * 
     * @var Releasr_CliCommand_List
     */
    private $_command;
    
    public function setUp()
    {
        $this->_command = new Releasr_CliCommand_List($this->_branchLister);
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
        $arguments = array('myproject');
        
        $this->_command->run($arguments);
    }
}