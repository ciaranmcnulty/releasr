<?php

/**
 * @package Releasr
 */
class Releasr_Controller_PreparerTest extends PHPUnit_Framework_Testcase
{
    /**
     * @var Releasr_Controller_Preparer
     */
    private $_preparer;
    
    /** 
     * @var Releasr_SvnRunner
     */
    private $_svnRunner;
    
    public function setUp()
    {
        $urlResolver = $this->getMock('Releasr_Repo_UrlResolver', array(), array(), '', FALSE);
        $this->_svnRunner = $this->getMock('Releasr_Repo_Runner');

        $urlResolver->expects($this->any())
            ->method('getTrunkUrlForProject')
            ->will($this->returnValue('http://trunk-url'));

        $urlResolver->expects($this->any())
            ->method('getBranchUrlForProject')
            ->will($this->returnValue('http://branch-url'));

        $this->_preparer = new Releasr_Controller_Preparer($urlResolver, $this->_svnRunner);
    }

    public function testPepareReleaseDoesSvnCopyWithCorrectParameters()
    {
        $this->_svnRunner->expects($this->once())
            ->method('copy')
            ->with(
                $this->equalTo('http://trunk-url'),
                $this->equalTo('http://branch-url/mybranch'),
                $this->equalTo('Creating release branch')
            );

        $this->_preparer->prepareRelease('myproject', 'mybranch');
    }


}