<?php

namespace Pop\Archive\Test;

use Pop\Archive\Archive;

class ZipTest extends \PHPUnit_Framework_TestCase
{

    public function testAddFiles()
    {
        $archive = new Archive(__DIR__ . '/test.zip');
        $archive->addFiles([__DIR__ . '/tmp/add.txt', __DIR__ . '/tmp/test.orig.zip']);
        $this->assertContains('add.txt', $archive->listFiles());
        unlink(__DIR__ . '/test.zip');
    }

    public function testAddDir()
    {
        $archive = new Archive(__DIR__ . '/test.zip');
        $archive->addFiles(__DIR__ . '/tmp');
        $this->assertContains('tmp/', $archive->adapter()->getDirs());
        $this->assertContains('tmp/add.txt', $archive->listFiles());
        unlink(__DIR__ . '/test.zip');
    }

}