<?php

class Releasr_Repo_RunnerTest extends PHPUnit_Framework_Testcase
{
    /**
    * @var Releasr_SvnRunner
    */
    private $_runner;
    
    public function setUp()
    {
        $this->_runner = $this->getMock('Releasr_Repo_Runner', array('_doShellCommand'));
    }
    
    public function testCopyDoesCorrectShellCommand()
    {
        $this->_runner->expects($this->any())
            ->method('_doShellCommand')
            ->will($this->returnValue('Committed revision 1234'));

        $this->_runner->expects($this->once())
            ->method('_doShellCommand')
            ->with(
                $this->logicalAnd(
                    $this->stringContains('svn copy'),
                    $this->stringContains('http://from'),
                    $this->stringContains('http://to'),
                    $this->stringContains('-m \'msg\'')
                )
            );

        $this->_runner->copy('http://from', 'http://to', 'msg');
    }

    public function testCopyReturnsResponseFromServer()
    {
        $this->_runner->expects($this->any())
            ->method('_doShellCommand')
            ->will($this->returnValue('Committed revision 1234'));

        $output = $this->_runner->copy('http://from', 'http://to', 'msg');       

        $this->assertSame('Committed revision 1234', $output);
    }

    /**
     * @expectedException Releasr_Exception_Repo
     */
    public function testCopyThrowsAnExceptionIfResponseDoesNotContainSuccessMessage()
    {
        $this->_runner->expects($this->any())
            ->method('_doShellCommand')
            ->will($this->returnValue('Something about an error'));

        $this->_runner->copy('http://from', 'http://to', 'msg');     
    }

    public function testListDoesCorrectShellCommand()
    {
        $this->_runner->expects($this->once())
            ->method('_doShellCommand')
            ->with(
                $this->logicalAnd(
                    $this->stringContains('svn list'),
                    $this->stringContains('http://url'),
                    $this->stringContains('--xml')
                )
            );

        $this->_runner->ls('http://url');
    }

    public function testListReturnsOutputFromShellCommand()
    {
        $this->_runner->expects($this->once())
            ->method('_doShellCommand')
            ->will($this->returnValue('OUTPUT'));

        $output = $this->_runner->ls('http://url');

        $this->assertSame('OUTPUT', $output);
    }

    public function testLogDoesSvnLogShellCommand()
    {
        $this->_runner->expects($this->once())
            ->method('_doShellCommand')
            ->with(
                $this->logicalAnd(
                    $this->stringContains('svn log'),
                    $this->stringContains('http://url'),
                    $this->stringContains('--xml')
                )
            )
            ->will($this->returnValue('OUTPUT'));

        $output = $this->_runner->log('http://url');

        $this->assertSame('OUTPUT', $output);
    }

    public function testLogAddsCorrectFlagToCommandWhenStopOnCopyParamIsTrue()
    {
        $this->_runner->expects($this->once())
            ->method('_doShellCommand')
            ->with($this->stringContains('--stop-on-copy'));

        $this->_runner->log('http://url', TRUE);
    }

    public function testLogAddsCorrectOptionWhenStartRevisionIsSpecified()
    {
        $this->_runner->expects($this->once())
            ->method('_doShellCommand')
            ->with($this->stringContains('-r1234:HEAD'));

        $this->_runner->log('http://url', FALSE, 1234);
    }
}