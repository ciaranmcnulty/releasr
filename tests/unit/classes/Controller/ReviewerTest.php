<?php

/**
 * @package Releasr
 */
class Releasr_Controller_ReviewerTest extends PHPUnit_Framework_Testcase
{
    /**
     * @var Releasr_Controller_Lister
     */
    private $_lister;

    /**
     * @var Releasr_Controller_Reviewer
     */
    private $_reviewer;

    /** 
     * @var Releasr_SvnRunner
     */
    private $_svnRunner;

    public function setUp()
    {
        $this->_config = $this->getMock('Releasr_Repo_UrlResolver', array(), array(), '', FALSE);
        $this->_svnRunner = $this->getMock('Releasr_Repo_Runner', array(), array(), '', FALSE);
        $this->_lister = $this->getMock('Releasr_Controller_Lister', array(), array(), '', FALSE);

        $this->_reviewer = new Releasr_Controller_Reviewer($this->_config, $this->_svnRunner, $this->_lister);  
    }

    public function testReviewerListsReleasesToFindOutWhichIsMostRecent()
    {
        $this->_setUpListerToReturnOneRevision();
        $this->_setUpRunnerToReturnSomeChanges($this->_svnRunner);

        $this->_lister->expects($this->once())
            ->method('getMostRecentRelease')
            ->with($this->equalTo('myproject'));

        $this->_reviewer->reviewRelease('myproject');
    }

    private function _setUpRunnerToReturnSomeChanges($runner)
    {
        $change = $this->getMock('Releasr_Repo_Change');
        $earliestChange = clone $change;
        $earliestChange->revision = 1234;

        $runner->expects($this->at(0))
            ->method('log')
            ->will($this->returnValue(array($change, $earliestChange)));
    }

    private function _setUpListerToReturnOneRevision()
    {
        $release = $this->getMock('Releasr_Repo_Release');
        $release->url = 'http://branch-url';

        $this->_lister->expects($this->any())
             ->method('getMostRecentRelease')
             ->will($this->returnValue($release));
    }

    public function testReviewerLogsLatestReleaseBranchToFindWhenItWasCreated()
    {
        $this->_setUpListerToReturnOneRevision();
        $this->_setUpRunnerToReturnSomeChanges($this->_svnRunner);

        $this->_svnRunner->expects($this->at(0))
            ->method('log')
            ->with(
                $this->equalTo('http://branch-url'),
                $this->equalTo(TRUE)
            );

        $this->_reviewer->reviewRelease('myproject');
    }
    
    public function testReviewerLogsTrunkFromBranchPointToHead()
    {
        $this->_setUpListerToReturnOneRevision();
        $this->_setUpRunnerToReturnSomeChanges($this->_svnRunner);

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

    public function testReviewerReturnsTrunkLogs()
    {
        $change = $this->getMock('Releasr_Repo_Change');
        $changes = array($change);

        $this->_setUpListerToReturnOneRevision();
        $this->_setUpRunnerToReturnSomeChanges($this->_svnRunner);

        $this->_svnRunner->expects($this->at(1))
            ->method('log')
            ->will($this->returnValue($changes));

        $result = $this->_reviewer->reviewRelease('myproject');

        $this->assertSame($changes, $result);
    }
}
