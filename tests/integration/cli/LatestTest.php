<?php

/**
* @package Releasr
*/
class Releasr_Integration_Cli_LatestTest  extends Releasr_Integration_Cli_Abstract
{
    public function testLatestOnEmptyRepo()
    {
        $result = shell_exec('releasr latest myproject');
        $this->assertContains('No releases', $result);
    }

    public function testListOnRepoWithOneRelease()
    {
        shell_exec('releasr prepare myproject releasename');
        
        $result = shell_exec('releasr latest myproject');
        
        $this->assertContains('file://', $result);
        $this->assertContains('myproject', $result);
    }
}