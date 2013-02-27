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
        $this->_setUpRunnerToReturnExampleListingXml($this->_runner);
            
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
        
    private function _setUpRunnerToReturnExampleListingXml($runner)
    {
        $runner->expects($this->any())
            ->method('_doShellCommand')
            ->will($this->returnValue(file_get_contents(dirname(__FILE__) . '/example-listing.xml')));
    }
    
    public function testListParsesXmlResultIntoCorrectNumberOfReleases()
    {
        $this->_setUpRunnerToReturnExampleListingXml($this->_runner);

        $releases = $this->_runner->ls('http://url');

        $this->assertCount(3, $releases);
    }

    public function testListParsesXmlResultIntoReleaseObjects()
    {
        $this->_setUpRunnerToReturnExampleListingXml($this->_runner);

        $releases = $this->_runner->ls('http://url');
        
        $this->assertInstanceOf('Releasr_Repo_Release', $releases[0]);  
    }
    
    public function testListsParsesXmlResultIntoCorrectPropertiesOnReleaseObjects()
    {
        $this->_setUpRunnerToReturnExampleListingXml($this->_runner);

        $releases = $this->_runner->ls('http://url');

        $this->assertAttributeSame('release-1234', 'name', $releases[0]);
        $this->assertAttributeSame('http://url/release-1234', 'url', $releases[0]);
    }

    /**
     * @expectedException Releasr_Exception_Repo
     */
    public function testListThrowsRepoExceptionIfResponseIsNotXml()
    {
        $this->_runner->expects($this->any())
            ->method('_doShellCommand')
            ->will($this->returnValue('SOME SORT OF ERROR'));

        $this->_runner->ls('http://url');
    }

    public function testLogDoesSvnLogShellCommand()
    {
        $this->_setUpRunnerToReturnExampleListingXml($this->_runner);

        $this->_runner->expects($this->once())
            ->method('_doShellCommand')
            ->with(
                $this->logicalAnd(
                    $this->stringContains('svn log'),
                    $this->stringContains('http://url'),
                    $this->stringContains('--xml')
                )
            );

        $output = $this->_runner->log('http://url');
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

        $this->_runner->log('http://url', TRUE);
    }

    public function testLogAddsCorrectOptionWhenStartRevisionIsSpecified()
    {
        $this->_setUpRunnerToReturnExampleLogXml($this->_runner);

        $this->_runner->expects($this->once())
            ->method('_doShellCommand')
            ->with($this->stringContains('-r1234:HEAD'));

        $this->_runner->log('http://url', FALSE, 1234);
    }

    /**
     * @expectedException Releasr_Exception_Repo
     */
    public function testLogThrowsExceptionIfServerResponseIsNotXml()
    {
        $this->_runner->expects($this->once())
            ->method('_doShellCommand')
            ->will($this->returnValue('NOT XML'));

        $this->_runner->log('http://url');
    }
    
    public function testLogBuildsChangeObjectsCorrectly()
    {
        $this->_setUpRunnerToReturnExampleLogXml($this->_runner);

        $changes = $this->_runner->log('http://url');

        $this->assertCount(2, $changes);
        $this->assertInstanceOf('Releasr_Repo_Change', $changes[0]);
        $this->assertAttributeSame('tom', 'author', $changes[0]);
        $this->assertAttributeSame('Late Message', 'comment', $changes[0]);
        $this->assertAttributeSame(2345, 'revision', $changes[0]);
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

        $this->_runner->externals('http://url');
    }

    public function testExternalsReturnsExternalsObjects()
    {
        $this->_setUpRunnerToReturnExampleExternalsXml($this->_runner);

        $result = $this->_runner->externals('http://url');

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

        $result = $this->_runner->externals('http://url');

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

        $this->_runner->externals('http://url');
    }
}