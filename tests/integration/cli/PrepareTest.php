<?php

/**
* @package Releasr
*/
class Releasr_Integration_Cli_PrepareTest  extends Releasr_Integration_Cli_Abstract
{
    public function testPrepareOutputsMessage()
    {
        $result = shell_exec('releasr prepare myproject mybranch');

        $this->assertContains('created branch', $result);
    }
    
    public function testPrepareCopiesTrunkFilesToCorrectPlace()
    {
        $this->_commitNewFileOnTrunk("Commit Message");
        shell_exec('releasr prepare myproject release-0');

        $result = shell_exec('svn list ' . escapeshellarg('file://' . $this->_repoPath . '/myproject/releases/release-0'));

        $this->assertContains('file.txt', $result);
    }
}