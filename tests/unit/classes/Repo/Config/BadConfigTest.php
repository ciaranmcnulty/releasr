<?php

/**
 * @package Releasr
 */
class Releasr_Repo_Config_BadConfigTest extends PHPUnit_Framework_Testcase
{
    
    public $_config;
    
    public function setUp()
    {
        $this->_config = $this->getMock('Releasr_Repo_Config', array('_doParseIniFile'), array(), '', FALSE);
        $this->_config ->expects($this->any())
            ->method('_doParseIniFile')
            ->will($this->returnValue(array()));
        $this->_config ->__construct(array(''));
    }
    
    /**
     * @expectedException Releasr_Exception_Config
     */
    public function testGetBranchUrlForProjectCausesAConfigExceptionIfReleasesUrlIsNotInConfig()
    { 
        $this->_config ->getBranchUrlForProject('myproject');
    }

    /**
     * @expectedException Releasr_Exception_Config
     */
    public function testGetTrunkUrlForProjectCausesAConfigExceptionIfReleasesUrlIsNotInConfig()
    { 
        $this->_config ->getTrunkUrlForProject('myproject');
    }
}