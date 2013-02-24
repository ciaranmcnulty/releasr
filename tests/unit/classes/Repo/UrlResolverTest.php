<?php

/**
 * @package Releasr
 */
class Releasr_Repo_UrlResolverTest extends PHPUnit_Framework_Testcase
{
    
    /**
     * @var Releasr_Config
     */    
    private $_config;
    
    /**
     * @var Releasr_Repo_UrlResolver
     */
    private $_resolver;
    
    public function setUp()
    {
        $this->_config = $this->getMock('Releasr_Config', array(), array(), '', FALSE);
        $this->_resolver = new Releasr_Repo_UrlResolver($this->_config);
    }

    public function testGetBranchUrlForProjectReturnsUrlWithPatternSpecifiedInConfig()
    { 
        $urlPattern = 'http://myserver/%PROJECT%/branches/releases';
        
        $this->_config->expects($this->once())
            ->method('getRequiredOption')
            ->with($this->equalTo('releases_url'))
            ->will($this->returnValue($urlPattern));
        
        $url = $this->_resolver->getBranchUrlForProject('myproject');
        
        $this->assertSame('http://myserver/myproject/branches/releases', $url);
    }

    public function testGetTrunkUrlForProjectReturnsUrlWithPatternSpecifiedInConfig()
    {
        $urlPattern = 'http://myserver/%PROJECT%/trunk';

        $this->_config->expects($this->once())
            ->method('getRequiredOption')
            ->with($this->equalTo('trunk_url'))
            ->will($this->returnValue($urlPattern));

        $url = $this->_resolver->getTrunkUrlForProject('myproject');

        $this->assertSame('http://myserver/myproject/trunk', $url);
    }

}