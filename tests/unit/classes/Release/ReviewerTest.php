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

    public function setUp()
    {
        $this->_config = $this->getMock('Releasr_Repo_UrlResolver', array(), array(), '', FALSE);

        $release = $this->getMock('Releasr_Repo_Release');
        $release->url = 'http://branch-url';
        
        $this->_lister = $this->getMock('Releasr_Release_Lister', array(), array(), '', FALSE);
        $this->_lister->expects($this->any())
            ->method('getMostRecentRelease')
            ->will($this->returnValue($release));
        
        $this->_reviewer = $this->getMock('Releasr_Release_Reviewer', array('_doShellCommand'), array($this->_config, $this->_lister));  

        $this->_reviewer->expects($this->any())
            ->method('_doShellCommand')
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
        $this->_reviewer->expects($this->at(0))
            ->method('_doShellCommand')
            ->with(
                $this->logicalAnd(
                    $this->stringContains('svn log'),
                    $this->stringContains('--xml'),
                    $this->stringContains('--stop-on-copy'),
                    $this->stringContains('http://branch-url')
                )
            );
        
        $this->_reviewer->reviewRelease('myproject');
    }

    /**
     * @expectedException Releasr_Exception_Repo
     */
    public function testReviewReleaseCausesAnExceptionWhenXmlFromBranchLogIsUnparseable()
    {    
        $badResponseReviewer = $this->getMock('Releasr_Release_Reviewer', array('_doShellCommand'), 
            array($this->_config, $this->_lister));
            
        $badResponseReviewer->expects($this->any())
            ->method('_doShellCommand')
            ->will($this->returnValue('not xml'));

        $releases = $badResponseReviewer->reviewRelease('myproject');
    }
    
    public function testReviewerLogsTrunk()
    {        
        $this->_config->expects($this->any())
            ->method('getTrunkUrlForProject')
            ->will($this->returnValue('http://trunk-url'));

        $this->_reviewer->expects($this->at(1))
            ->method('_doShellCommand')
            ->with(
                $this->logicalAnd(
                    $this->stringContains('svn log'),
                    $this->stringContains('--xml'),
                    $this->stringContains('http://trunk-url')
                )
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

    public function testReviewerLogsTrunkWithCorrectRevisions()
    {
        $this->_reviewer->expects($this->at(1))
            ->method('_doShellCommand')
            ->with(
                $this->stringContains('-r1234:HEAD') // magic number derived from example-log.xml
            );

        $this->_reviewer->reviewRelease('myproject');
    }

    /**
     * @expectedException Releasr_Exception_Repo
     */
    public function testReviewReleaseCausesAnExceptionWhenXmlFromTrunkhLogIsUnparseable()
    {    
        $badResponseReviewer = $this->getMock('Releasr_Release_Reviewer', array('_doShellCommand'), 
            array($this->_config, $this->_lister));

        $badResponseReviewer->expects($this->at(0))
            ->method('_doShellCommand')
            ->will($this->returnValue(file_get_contents(dirname(__FILE__).'/example-log.xml')));

        $badResponseReviewer->expects($this->at(1))
            ->method('_doShellCommand')
            ->will($this->returnValue('not xml'));

        $releases = $badResponseReviewer->reviewRelease('myproject');
    }
}
