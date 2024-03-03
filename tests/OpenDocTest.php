<?php

namespace Bfg\OpenDoc\Tests;

use Bfg\OpenDoc\Facades\OpenDoc;
use Bfg\OpenDoc\ServiceProvider;
use Orchestra\Testbench\TestCase;

class OpenDocTest extends TestCase
{
    protected function getPackageProviders($app)
    {
        return [ServiceProvider::class];
    }

    protected function getPackageAliases($app)
    {
        return [
            'open-doc' => OpenDoc::class,
        ];
    }

    public function testExample()
    {
        $this->assertEquals(1, 1);
    }
}
