<?php

/**
* @package Releasr
*/
class Releasr_Integration_Cli_ReviewTest  extends Releasr_Integration_Cli_Abstract
{
    public function testReviewOnNoChanges()
    {
        shell_exec('releasr prepare myproject release-0');

        $result = shell_exec('releasr review myproject');

        $this->assertContains('No changes', $result);
    }

    public function testReviewShowsChangesOnTrunk()
    {
        shell_exec('releasr prepare myproject release-0');
        $this->_commitNewFileOnTrunk("Commit Message");

        $result = shell_exec('releasr review myproject');

        $this->assertContains('Commit Message', $result);
    }
}