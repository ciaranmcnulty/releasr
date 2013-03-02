<?php

class Releasr_Repo_ExternalTest extends PHPUnit_Framework_Testcase
{
    public function testHasUnversionedExternalsReturnsFalseWhenThereAreNoExternals()
    {
        $external = new Releasr_Repo_External();
        $external->property = '';

        $result = $external->hasUnversionedExternals();

        $this->assertFalse($result);
    }

    public function testHasUnversionedExternalsReturnsFalseWhenAllExternalsAreVersioned()
    {
        $external = new Releasr_Repo_External();
        $external->property = 'Foo -r1242 http://foo
Bar -r 12424 http://bar';

        $result = $external->hasUnversionedExternals();

        $this->assertFalse($result);
    }

    public function testHasUnversionedExternalsReturnsTrueWhenAnExternalIsNotVersioned()
    {
        $external = new Releasr_Repo_External();
        $external->property = 'Foo http://foo
Bar -r 12424 http://bar';

        $result = $external->hasUnversionedExternals();

        $this->assertTrue($result);
    }
}