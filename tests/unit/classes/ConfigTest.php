<?php

/**
 * Test for config object
 *
 * @package Releasr
 */
class Releasr_ConfigTest extends PHPUnit_Framework_Testcase
{
    private $_config;
    
    public function setUp()
    {
         $this->_config = $this->getMock('Releasr_Config', array('_doParseIniFile'), array(), '', FALSE);
    }

    /**
     * @expectedException Releasr_Exception_Config
     */
    public function testConfigTriesToGetConfigFromEachFileThenThrowsAnExceptionWhenNoneAreParsable()
    {
        $this->_config->expects($this->any())
            ->method('_doParseIniFile')
            ->will($this->returnValue(FALSE));
                
        $this->_config->expects($this->at(0))
            ->method('_doParseIniFile')
            ->with($this->equalTo('config1'));
            
        $this->_config->expects($this->at(1))
            ->method('_doParseIniFile')
            ->with($this->equalTo('config2'));
        
        $this->_config->__construct(array('config1', 'config2'));
    }

    public function testConfigDoesNotTryAndParseSecondFileIfFirstOneReturnsConfig()
    {
        $this->_config->expects($this->any())
            ->method('_doParseIniFile')
            ->will($this->returnValue(array()));
        
        $this->_config->expects($this->once())
            ->method('_doParseIniFile')
            ->with($this->equalTo('config1'));
        
        $this->_config->__construct(array('config1', 'config2'));
    }
    
    public function testGetOptionReturnsValueIfItIsPresentInConfig()
    {
        $this->_constructConfigWithOptions(array('foo'=>'FOO'));
        
        $result = $this->_config->getOption('foo');
        
        $this->assertSame('FOO', $result);
    }

    public function testGetOptionReturnsNullIfNotPresentInConfig()
    {
        $this->_constructConfigWithOptions(array('foo'=>'FOO'));
        
        $result = $this->_config->getOption('bar');
        
        $this->assertSame(NULL, $result);
    }

    public function testGetRequiredOptionReturnsValueIfItIsPresentInConfig()
    {
        $this->_constructConfigWithOptions(array('foo'=>'FOO'));

        $result = $this->_config->getRequiredOption('foo');

        $this->assertSame('FOO', $result);
    }

    /** 
     * @expectedException Releasr_Exception_Config
     */
    public function testGetRequiredOptionThrowsExceptionIfItIsNotPresentInConfig()
    {
        $this->_constructConfigWithOptions(array('foo'=>'FOO'));

        $result = $this->_config->getRequiredOption('bar');
    }
    
    private function _constructConfigWithOptions($options)
    {
        $this->_config->expects($this->any())
            ->method('_doParseIniFile')
            ->will($this->returnValue($options));

        $this->_config->__construct(array('config1', 'config2'));
    }
}