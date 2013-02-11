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
     * @var array Example of the repository config
     */
    private $_repoConfig;
    
    public function setUp()
    {
        $this->_repoConfig = array(
            'releases_url' => 'http://example/%PROJECT%/releases'
        );
        
        $this->_lister = $this->getMock('Releasr_Release_Lister', array('_doShellCommand'), array($this->_repoConfig));
        $this->_exampleResponse = file_get_contents(dirname(__FILE__).'/example-listing.xml');
        $this->_lister->expects($this->any())
            ->method('_doShellCommand')
            ->will($this->returnValue($this->_exampleResponse));
        
    }
    
    /**
     * @expectedException Releasr_Exception_Config
     */
    public function testListReleasesCausesAConfigExceptionIfReleasesUrlIsNotInConfig()
    {
        $config = array('nothing here');
        
        $badlyConfiguredlister = $this->getMock('Releasr_Release_Lister', array('_doShellCommand'), array($config));
        
        $badlyConfiguredlister->listReleases('myproject');
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
        $releases = $this->_lister->listReleases('myproject');

        $this->assertAttributeSame('release-1234', 'name', $releases[0]);
    }
    
    /**
     * @expectedException Releasr_Exception_Repo
     */
    public function testListReleasesCausesAnExceptionWhenXmlIsUnparseable()
    {    
        $badResponseLister = $this->getMock('Releasr_Release_Lister', array('_doShellCommand'), array($this->_repoConfig));
        $badResponseLister->expects($this->any())
            ->method('_doShellCommand')
            ->will($this->returnValue('not xml'));

        $releases = $badResponseLister->listReleases('myproject');
    }
    
}