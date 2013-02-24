<?php

/**
 * @package Releasr
 */
class Releasr_Repo_UrlResolver_ArrayConfigTest extends PHPUnit_Framework_Testcase
{
    
    /**
     * @var Releasr_Repo_Config
     */
    private $_repo;
    
    public function setUp()
    {
        $this->_repo = $this->getMock('Releasr_Repo_UrlResolver', array('_doParseIniFile'), array(), '', FALSE);
    }

    /**
     * @expectedException Releasr_Exception_Config
     */
    public function testConfigTriesToGetConfigFromEachFileThenThrowsAnExceptionWhenNoneAreParsable()
    {
        $this->_repo->expects($this->any())
            ->method('_doParseIniFile')
            ->will($this->returnValue(FALSE));
                
        $this->_repo->expects($this->at(0))
            ->method('_doParseIniFile')
            ->with($this->equalTo('config1'));
            
        $this->_repo->expects($this->at(1))
            ->method('_doParseIniFile')
            ->with($this->equalTo('config2'));
        
        $this->_repo->__construct(array('config1', 'config2'));
    }
    
    public function testConfigDoesNotTryAndParseSecondFileIfFirstOneReturnsConfig()
    {
        $this->_repo->expects($this->any())
            ->method('_doParseIniFile')
            ->will($this->returnValue(array()));
        
        $this->_repo->expects($this->once())
            ->method('_doParseIniFile')
            ->with($this->equalTo('config1'));
        
        $this->_repo->__construct(array('config1', 'config2'));
    }
}