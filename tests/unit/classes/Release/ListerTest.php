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

    /** 
    * @var Releasr_SvnRunner
    */
    private $_svnRunner;

    public function setUp()
    {
        $this->_config = $this->getMock('Releasr_Repo_UrlResolver', array(), array(), '', FALSE);
        $this->_svnRunner = $this->getMock('Releasr_Repo_Runner');

        $this->_exampleResponse = file_get_contents(dirname(__FILE__).'/example-listing.xml');
        $this->_svnRunner->expects($this->any())
            ->method('ls')
            ->will($this->returnValue($this->_exampleResponse));

        $this->_lister = new Releasr_Release_Lister($this->_config, $this->_svnRunner);
    }

    public function testListReleasesDoesSvnListCommand()
    {
        $this->_svnRunner->expects($this->once())
            ->method('ls');

        $this->_lister->listReleases('myproject');
    }

    public function testListReleasesDoesAShellCommandWithTheCorrectSvnUrl()
    {
        $this->_config->expects($this->any())
            ->method('getBranchUrlForProject')
            ->will($this->returnValue('http://example/myproject/releases'));
        
        $this->_svnRunner->expects($this->once())
            ->method('ls')
            ->with($this->equalTo('http://example/myproject/releases'));

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
        $badResponseRunner = $this->getMock('Releasr_Repo_Runner');
        $lister = new Releasr_Release_Lister($this->_config, $badResponseRunner);
        $badResponseRunner->expects($this->any())
            ->method('ls')
            ->will($this->returnValue('not xml'));
    
        $releases = $lister->listReleases('myproject');
    }
}