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
    
    /**
     * @var array Releasr_Repo_External mocks
     */
    private $_externals;
    
    public function setUp()
    {
        $urlResolver = $this->getMock('Releasr_Repo_UrlResolver', array(), array(), '', FALSE);

        $urlResolver->expects($this->any())
            ->method('getTrunkUrlForProject')
            ->will($this->returnValue('http://trunk-url'));

        $urlResolver->expects($this->any())
            ->method('getBranchUrlForProject')
            ->will($this->returnValue('http://branch-url'));

        $this->_externals = array(
            $this->getMock('Releasr_Repo_External'),
            $this->getMock('Releasr_Repo_External')
        );

        $this->_externals[0]->expects($this->any())
            ->method('hasUnversionedExternals')
            ->will($this->returnValue(TRUE));

        $this->_svnRunner = $this->getMock('Releasr_Repo_Runner', array(), array(), '', FALSE);
        $this->_svnRunner->expects($this->any())
            ->method('externals')
            ->will(
                $this->returnValue($this->_externals)
            );

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

    public function testPepareReleaseGetsAnExternalsReport()
    {
        $this->_svnRunner->expects($this->once())
            ->method('externals')
            ->with(
                $this->equalTo('http://branch-url/mybranch')
            );

        $this->_preparer->prepareRelease('myproject', 'mybranch');
    }

    public function testPepareReleaseReturnsAnyUnversionedExternalsFound()
    {
        $expected = array($this->_externals[0]);

        $result = $this->_preparer->prepareRelease('myproject', 'mybranch');

        $this->assertSame($expected, $result);
    }
}