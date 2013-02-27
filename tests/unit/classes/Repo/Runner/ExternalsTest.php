<?php

class Releasr_Repo_Runner_ExternalsTest extends PHPUnit_Framework_Testcase
{
    /**
    * @var Releasr_SvnRunner
    */
    private $_runner;

    public function setUp()
    {
        $this->_runner = $this->getMock('Releasr_Repo_Runner_Externals', array('_doShellCommand'));
    }

    public function testExternalsDoesCorrectSvnCommand()
    {
        $this->_setUpRunnerToReturnExampleExternalsXml($this->_runner);

        $this->_runner->expects($this->once())
            ->method('_doShellCommand')
            ->with(
                $this->logicalAnd(
                    $this->stringContains('svn propget'),
                    $this->stringContains('svn:externals'),
                    $this->stringContains('-R'),
                    $this->stringContains('--xml'),
                    $this->stringContains('http://url')
                )
            );

        $this->_runner->run('http://url');
    }

    public function testExternalsReturnsExternalsObjects()
    {
        $this->_setUpRunnerToReturnExampleExternalsXml($this->_runner);

        $result = $this->_runner->run('http://url');

        $this->assertCount(2, $result);
        $this->assertInstanceOf('Releasr_Repo_External', $result[0]);
    }

    private function _setUpRunnerToReturnExampleExternalsXml($runner)
    {
        $runner->expects($this->any())
            ->method('_doShellCommand')
            ->will($this->returnValue(file_get_contents(dirname(__FILE__) . '/example-externals.xml')));
    }

    public function testExternalsReturnsExternalsObjectsWithCorrectPropertiesSet()
    {
        $this->_setUpRunnerToReturnExampleExternalsXml($this->_runner);

        $result = $this->_runner->run('http://url');

        $this->assertAttributeSame('http://repo/library1', 'path', $result[0]);
        $this->assertContains('http://foo',  $result[0]->property);
    }

    /**
     * @expectedException Releasr_Exception_Repo
     */
    public function testExternalsThrowsExceptionIfRepoResultIsNotParsable()
    {
        $this->_runner->expects($this->any())
            ->method('_doShellCommand')
            ->will($this->returnValue('NOT XML'));

        $this->_runner->run('http://url');
    }
}