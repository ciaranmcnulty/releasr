<?php

class Releasr_Repo_Runner_ListTest extends PHPUnit_Framework_Testcase
{
    /**
    * @var Releasr_SvnRunner
    */
    private $_runner;
    
    public function setUp()
    {
        $this->_runner = $this->getMock('Releasr_Repo_Runner_List', array('_doShellCommand'));
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

        $this->_runner->run('http://url');
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

        $releases = $this->_runner->run('http://url');

        $this->assertCount(3, $releases);
    }

    public function testListParsesXmlResultIntoReleaseObjects()
    {
        $this->_setUpRunnerToReturnExampleListingXml($this->_runner);

        $releases = $this->_runner->run('http://url');

        $this->assertInstanceOf('Releasr_Repo_Release', $releases[0]);  
    }

    public function testListsParsesXmlResultIntoCorrectPropertiesOnReleaseObjects()
    {
        $this->_setUpRunnerToReturnExampleListingXml($this->_runner);

        $releases = $this->_runner->run('http://url');

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

        $this->_runner->run('http://url');
    }
}