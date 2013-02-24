<?php

/**
 * @package Releasr
 */
class Releasr_Repo_UrlResolver_Test extends PHPUnit_Framework_Testcase
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
        $this->_repo = $this->getMock('Releasr_Repo_UrlResolver', array('_doParseIniFile'), array(), '', FALSE);
        $this->_repo->expects($this->any())
            ->method('_doParseIniFile')
            ->will($this->returnValue($config));
        $this->_repo->__construct(array('config'));
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

}