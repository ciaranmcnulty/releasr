<?php

/**
 * @package Releasr
 */
class Releasr_Controller_ListerTest extends PHPUnit_Framework_Testcase
{
    /**
     * @var Releasr_Controller_Lister The lister under test
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
        $this->_lister = new Releasr_Controller_Lister($this->_config, $this->_svnRunner);
    }

    public function testListReleasesCallsTheSvnRunnerCorrectlyToGetListing()
    {
        $this->_svnRunner->expects($this->any())
            ->method('ls')
            ->will($this->returnValue(array()));
        
        $this->_config->expects($this->any())
            ->method('getBranchUrlForProject')
            ->will($this->returnValue('http://example/myproject/releases'));
        
        $this->_svnRunner->expects($this->once())
            ->method('ls')
            ->with($this->equalTo('http://example/myproject/releases'));

        $this->_lister->listReleases('myproject');
    }

    public function testGetMostRecentReleaseReturnsTheMostRecentReleaseByDate()
    {
        $release = $this->getMock('Releasr_Release');
        $release2 = clone $release;
        
        $release->date = new DateTime('-1 day');
        $release2->date = new DateTime('-2 days');

        $this->_svnRunner->expects($this->any())
            ->method('ls')
            ->will($this->returnValue(array($release, $release2)));

        $latest = $this->_lister->getMostRecentRelease('myproject');

        $this->assertSame($release, $latest);
    }
}