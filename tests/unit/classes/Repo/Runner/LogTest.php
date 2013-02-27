<?php

class Releasr_Repo_Runner_LogTest extends PHPUnit_Framework_Testcase
{
    /**
    * @var Releasr_SvnRunner
    */
    private $_runner;

    public function setUp()
    {
        $this->_runner = $this->getMock('Releasr_Repo_Runner_Log', array('_doShellCommand'));
    }

    public function testLogDoesSvnLogShellCommand()
    {
        $this->_setUpRunnerToReturnExampleLogXml($this->_runner);

        $this->_runner->expects($this->once())
            ->method('_doShellCommand')
            ->with(
                $this->logicalAnd(
                    $this->stringContains('svn log'),
                    $this->stringContains('http://url'),
                    $this->stringContains('--xml')
                )
            );

        $output = $this->_runner->run('http://url');
    }

    private function _setUpRunnerToReturnExampleLogXml($runner)
    {
        $runner->expects($this->any())
            ->method('_doShellCommand')
            ->will($this->returnValue(file_get_contents(dirname(__FILE__) . '/example-log.xml')));
    }

    public function testLogAddsCorrectFlagToCommandWhenStopOnCopyParamIsTrue()
    {
        $this->_setUpRunnerToReturnExampleLogXml($this->_runner);

        $this->_runner->expects($this->once())
            ->method('_doShellCommand')
            ->with($this->stringContains('--stop-on-copy'));

        $this->_runner->run('http://url', TRUE);
    }

    public function testLogAddsCorrectOptionWhenStartRevisionIsSpecified()
    {
        $this->_setUpRunnerToReturnExampleLogXml($this->_runner);

        $this->_runner->expects($this->once())
            ->method('_doShellCommand')
            ->with($this->stringContains('-r1234:HEAD'));

        $this->_runner->run('http://url', FALSE, 1234);
    }

    /**
     * @expectedException Releasr_Exception_Repo
     */
    public function testLogThrowsExceptionIfServerResponseIsNotXml()
    {
        $this->_runner->expects($this->once())
            ->method('_doShellCommand')
            ->will($this->returnValue('NOT XML'));

        $this->_runner->run('http://url');
    }
    
    public function testLogBuildsChangeObjectsCorrectly()
    {
        $this->_setUpRunnerToReturnExampleLogXml($this->_runner);

        $changes = $this->_runner->run('http://url');

        $this->assertCount(2, $changes);
        $this->assertInstanceOf('Releasr_Repo_Change', $changes[0]);
        $this->assertAttributeSame('tom', 'author', $changes[0]);
        $this->assertAttributeSame('Late Message', 'comment', $changes[0]);
        $this->assertAttributeSame(2345, 'revision', $changes[0]);
    }

}