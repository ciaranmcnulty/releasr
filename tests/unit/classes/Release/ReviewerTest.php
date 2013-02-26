<?php

/**
 * @package Releasr
 */
class Releasr_Release_ReviewerTest extends PHPUnit_Framework_Testcase
{
    /**
     * @var Releasr_Release_Lister
     */
    private $_lister;

    /**
     * @var Releasr_Release_Reviewer
     */
    private $_reviewer;

    /** 
     * @var Releasr_SvnRunner
     */
    private $_svnRunner;

    public function setUp()
    {
        $this->_config = $this->getMock('Releasr_Repo_UrlResolver', array(), array(), '', FALSE);
        $this->_svnRunner = $this->getMock('Releasr_Repo_Runner');

        $release = $this->getMock('Releasr_Repo_Release');
        $release->url = 'http://branch-url';
        
        $this->_lister = $this->getMock('Releasr_Release_Lister', array(), array(), '', FALSE);
        $this->_lister->expects($this->any())
            ->method('getMostRecentRelease')
            ->will($this->returnValue($release));
        
        $this->_reviewer = new Releasr_Release_Reviewer($this->_config, $this->_svnRunner, $this->_lister);  
            
        $this->_svnRunner->expects($this->any())
            ->method('log')
            ->will($this->returnValue(file_get_contents(dirname(__FILE__).'/example-log.xml')));
    }

    public function testReviewerListsReleasesToFindOutWhichIsMostRecent()
    {
        $this->_lister->expects($this->once())
            ->method('getMostRecentRelease')
            ->with($this->equalTo('myproject'));
        
        $this->_reviewer->reviewRelease('myproject');
    }

    public function testReviewerLogsLatestReleaseBranchUsingCorrectUrlAndOptions()
    {
        $this->_svnRunner->expects($this->at(0))
            ->method('log')
            ->with(
                $this->equalTo('http://branch-url'),
                $this->equalTo(TRUE)
            );

        $this->_reviewer->reviewRelease('myproject');
    }

    public function testReviewerLogsTrunk()
    {        
        $this->_config->expects($this->any())
            ->method('getTrunkUrlForProject')
            ->will($this->returnValue('http://trunk-url'));

        $this->_svnRunner->expects($this->at(1))
            ->method('log')
            ->with(
                $this->equalTo('http://trunk-url'),
                $this->equalTo(FALSE),
                $this->equalTo(1234)
            );

        $this->_reviewer->reviewRelease('myproject');
    }

    public function testReviewerBuildsChangeObjectsFromTrunkLogResults()
    {
        $changes = $this->_reviewer->reviewRelease('myproject');
        
        $this->assertCount(2, $changes);
        $this->assertInstanceOf('Releasr_Repo_Change', $changes[0]);
        $this->assertAttributeSame('tom', 'author', $changes[0]);
        $this->assertAttributeSame('Late Message', 'comment', $changes[0]);
    }

}
