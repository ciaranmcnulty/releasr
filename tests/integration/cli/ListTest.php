<?php

/**
* @package Releasr
*/
class Releasr_Integration_Cli_ListTest  extends Releasr_Integration_Cli_Abstract
{
    public function testListOnEmptyRepo()
    {
        $result = shell_exec('releasr list myproject');
        $this->assertContains('No releases found', $result);
    }

    public function testListOnRepoWithOneRelease()
    {
        shell_exec('releasr prepare myproject releasename');
        
        $result = shell_exec('releasr list myproject');
        
        $this->assertContains('release found', $result);
        $this->assertContains('releasename', $result);
    }
}