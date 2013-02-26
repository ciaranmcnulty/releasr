<?php

/**
 * @package Releasr
 */
class Releasr_Release_PreparerTest extends PHPUnit_Framework_Testcase
{
    /**
     * @var Releasr_Release_Preparer
     */
    private $_preparer;
    
    public function setUp()
    {
        $config = $this->getMock('Releasr_Repo_UrlResolver', array(), array(), '', FALSE);

        $config->expects($this->any())
            ->method('getTrunkUrlForProject')
            ->will($this->returnValue('http://trunk-url'));

        $config->expects($this->any())
            ->method('getBranchUrlForProject')
            ->will($this->returnValue('http://branch-url'));

        $this->_preparer = $this->getMock('Releasr_Release_Preparer', array('_doShellCommand'), array($config));
    }

    public function testPepareReleaseDoesSvnCopyWithCorrectParameters()
    {
        $this->_setRepoCopyResponse('Committed revision 12345');
        
        $this->_preparer->expects($this->at(0))
            ->method('_doShellCommand')
            ->with(
                $this->logicalAnd(
                    $this->stringContains('svn copy'),
                    $this->stringContains('http://trunk-url'),
                    $this->stringContains('http://branch-url/mybranch')
                )
        );
        
        $this->_preparer->prepareRelease('myproject', 'mybranch');
    }

    public function testPrepareReleaseCorrectlySetsCommitMessage()
    {
        $this->_setRepoCopyResponse('Committed revision 12345');
        
        $this->_preparer->expects($this->at(0))
            ->method('_doShellCommand')
            ->with($this->stringContains('-m \'Creating release branch\''));

        $this->_preparer->prepareRelease('myproject', 'mybranch');
    }

    /**
     * @expectedException Releasr_Exception_Repo
     */
    public function testPrepareReleaseThrowsExceptionIfRepoResponseIsNotWhatWasExpected()
    {
        $this->_setRepoCopyResponse('Some sort of error');
        $this->_preparer->prepareRelease('myproject', 'mybranch');        
    }
    
    /**
     * Sets the repository response message to the branch copy request
     */ 
    protected function _setRepoCopyResponse($response)
    {
        $this->_preparer->expects($this->at(0))
            ->method('_doShellCommand')
            ->will($this->returnValue($response));
    }
}