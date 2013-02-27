<?php

class Releasr_Repo_RunnerTest extends PHPUnit_Framework_Testcase
{
    /**
    * @var Releasr_SvnRunner
    */
    private $_runner;

    /**
     * @var array
     */
    private $_runners;

    public function setUp()
    {
        $this->_runners = array(
            'copy' => $this->getMock('Releasr_Repo_Runner_Copy'),
            'externals' => $this->getMock('Releasr_Repo_Runner_Externals'),
            'list' => $this->getMock('Releasr_Repo_Runner_List'),
            'log' => $this->getMock('Releasr_Repo_Runner_Log')
        );
        $this->_runner = new Releasr_Repo_Runner($this->_runners);
    }

    /**
     * @dataProvider getSubcommandOptions
     */
    public function testEachCommandCallsCorrectSubcommand($subCommand, $methodName, $params)
    {
        $this->_runners[$subCommand]->expects($this->once())
            ->method('run')
            ->will($this->returnValue('FOO'));

        $response = call_user_func_array(array($this->_runner, $methodName), $params);

        $this->assertSame('FOO', $response);
    }

    public function getSubcommandOptions()
    {
        return array(
            array('copy', 'copy', array('from', 'to', 'msg')),
            array('list', 'ls', array('url')),
            array('log', 'log', array('url')),
            array('externals', 'externals', array('url'))
        );
    }
}