<?php

/**
 * Commonalities in Meta tests
 *
 * @package Releasr
 */
abstract class Releasr_CliCommand_Meta_AbstractTest extends PHPUnit_Framework_Testcase
{
    /**
     * @var Releasr_CliCommand_Help
     */
    protected $_command;

    /**
     * @var Releasr_CliCommand_Interface
     */
    protected $_mockCommand;
    
    public function setUp()
    {
        $this->_mockCommand = $this->getMock('Releasr_CliCommand_Interface');
    }
}