<?php

/**
 * @package Releasr
 */
class Releasr_Release_ListerTest extends PHPUnit_Framework_Testcase
{
    /**
     * @var Releasr_Release_Lister The lister under test
     */
    private $_lister;

    /**
     * @var string Some example XML that might be returned by a list command
     */
    private $_exampleResponse;

    /**
     * @var Releasr_Repo_Config
     */
    private $_config;

    public function setUp()
    {
        $this->_config = $this->getMock('Releasr_Repo_UrlResolver', array(), array(), '', FALSE);
        
        $this->_lister = $this->getMock('Releasr_Release_Lister', array('_doShellCommand'), array($this->_config));
        $this->_exampleResponse = file_get_contents(dirname(__FILE__).'/example-listing.xml');
        $this->_lister->expects($this->any())
            ->method('_doShellCommand')
            ->will($this->returnValue($this->_exampleResponse));
    }

    public function testListReleasesCausesAShellCommandToBeRun()
    {
        $this->_lister->expects($this->once())
            ->method('_doShellCommand');
        
        $this->_lister->listReleases('myproject');
    }

    public function testListReleasesDoesSvnListCommand()
    {
        $this->_lister->expects($this->any())
            ->method('_doShellCommand')
            ->with($this->matchesRegularExpression('/^svn list/'));

        $this->_lister->listReleases('myproject');
    }

    public function testListReleasesDoesAShellCommandWithTheCorrectSvnUrl()
    {
        $this->_config->expects($this->any())
            ->method('getBranchUrlForProject')
            ->will($this->returnValue('http://example/myproject/releases'));
        
        $this->_lister->expects($this->any())
            ->method('_doShellCommand')
            ->with($this->stringContains('http://example/myproject/releases'));

        $this->_lister->listReleases('myproject');
    }

    public function testListReleasesAsksForResultsInXmlFormat()
    {
        $this->_lister->expects($this->any())
            ->method('_doShellCommand')
            ->with($this->stringContains('--xml'));

        $this->_lister->listReleases('myproject');
    }
    
    public function testListReleasesParsesXmlResultIntoCorrectNumberOfReleases()
    {
        $releases = $this->_lister->listReleases('myproject');
        
        $this->assertCount(3, $releases);
    }

    public function testListReleasesParsesXmlResultIntoReleaseObjects()
    {
        $releases = $this->_lister->listReleases('myproject');
        
        $this->assertInstanceOf('Releasr_Repo_Release', $releases[0]);  
    }

    public function testListReleasesParsesXmlResultIntoCorrectPropertiesOnReleaseObjects()
    {
        $this->_config->expects($this->any())
            ->method('getBranchUrlForProject')
            ->will($this->returnValue('http://example/myproject/releases'));
        
        $releases = $this->_lister->listReleases('myproject');

        $this->assertAttributeSame('release-1234', 'name', $releases[0]);
        $this->assertAttributeSame('http://example/myproject/releases/release-1234', 'url', $releases[0]);
    }

    public function testGetMostRecentReleaseReturnsTheMostRecentReleaseByDate()
    {
        $release = $this->_lister->getMostRecentRelease('myproject');

        $this->assertInstanceOf('Releasr_Repo_Release', $release);
        $this->assertAttributeSame('release-2345', 'name', $release);
    }

    /**
     * @expectedException Releasr_Exception_Repo
     */
    public function testListReleasesCausesAnExceptionWhenXmlIsUnparseable()
    {    
        $badResponseLister = $this->getMock('Releasr_Release_Lister', array('_doShellCommand'), array($this->_config));
        $badResponseLister->expects($this->any())
            ->method('_doShellCommand')
            ->will($this->returnValue('not xml'));

        $releases = $badResponseLister->listReleases('myproject');
    }
}