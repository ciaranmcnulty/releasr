<?php

class Releasr_Repo_Runner_CopyTest extends PHPUnit_Framework_Testcase
{
    /**
    * @var Releasr_SvnRunner
    */
    private $_runner;
    
    public function setUp()
    {
        $this->_runner = $this->getMock('Releasr_Repo_Runner_Copy', array('_doShellCommand'));
    }
    
    public function testCopyDoesCorrectSvnCopyCommand()
    {
        $this->_setUpCopyResponse('Committed revision 1234');

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

        $this->_runner->run('http://from', 'http://to', 'msg');
    }
    
    private function _setUpCopyResponse($msg)
    {
        $this->_runner->expects($this->any())
            ->method('_doShellCommand')
            ->will($this->returnValue($msg));
    }

    public function testCopyReturnsResponseFromServer()
    {
        $this->_setUpCopyResponse('Committed revision 1234');

        $output = $this->_runner->run('http://from', 'http://to', 'msg');

        $this->assertSame('Committed revision 1234', $output);
    }

    /**
     * @expectedException Releasr_Exception_Repo
     */
    public function testCopyThrowsAnExceptionIfResponseDoesNotContainSuccessMessage()
    {
        $this->_setUpCopyResponse('Some sort of error occurred.');

        $this->_runner->run('http://from', 'http://to', 'msg');
    }
}