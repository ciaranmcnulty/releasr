<?php

/**
 * @package Releasr
 */
class Releasr_Repo_ConfigTest extends PHPUnit_Framework_Testcase
{
    
    /**
     * @var Releasr_Repo_Config
     */
    private $_repo;
    
    public function setUp()
    {
        $config = array(
            'releases_url' => 'http://myserver/%PROJECT%/branches/releases',
            'trunk_url' => 'http://myserver/%PROJECT%/trunk'
        );
        
        // convoluted setup to get around the parse_ini_file in the constructor
        $this->_repo = $this->getMock('Releasr_Repo_Config', array('_doParseIniFile'), array(), '', FALSE);
        $this->_repo->expects($this->any())
            ->method('_doParseIniFile')
            ->will($this->returnValue($config));
        $this->_repo->__construct('');
    }

    public function testConfigReadsSpecifiedConfigFile()
    {
        $this->_repo->expects($this->once())
            ->method('_doParseIniFile')
            ->with($this->equalTo('configfile.ini'));
        
        $this->_repo->__construct('configfile.ini');
    }

    public function testGetBranchUrlForProjectReturnsUrlWithPatternSpecifiedInConfig()
    { 
        $url = $this->_repo->getBranchUrlForProject('myproject');
        
        $this->assertSame('http://myserver/myproject/branches/releases', $url);
    }

    public function testGetTrunkUrlForProjectReturnsUrlWithPatternSpecifiedInConfig()
    {
        $url = $this->_repo->getTrunkUrlForProject('myproject');

        $this->assertSame('http://myserver/myproject/trunk', $url);
    }

    /**
     * @expectedException Releasr_Exception_Config
     */
    public function testGetBranchUrlForProjectCausesAConfigExceptionIfReleasesUrlIsNotInConfig()
    { 
        $badConfig = $this->getMock('Releasr_Repo_Config', array('_doParseIniFile'), array(), '', FALSE);
        $badConfig->expects($this->any())
            ->method('_doParseIniFile')
            ->will($this->returnValue(array()));
        $badConfig->__construct('');
        
        $badConfig->getBranchUrlForProject('myproject');
    }

    /**
     * @expectedException Releasr_Exception_Config
     */
    public function testGetTrunkUrlForProjectCausesAConfigExceptionIfReleasesUrlIsNotInConfig()
    { 
        $badConfig = $this->getMock('Releasr_Repo_Config', array('_doParseIniFile'), array(), '', FALSE);
        $badConfig->expects($this->any())
            ->method('_doParseIniFile')
            ->will($this->returnValue(array()));
        $badConfig->__construct('');
        
        $badConfig->getTrunkUrlForProject('myproject');
    }
}