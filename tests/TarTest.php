<?php

namespace Pop\Archive\Test;

use Pop\Archive\Archive;

class TarTest extends \PHPUnit_Framework_TestCase
{

    public function testAddDir()
    {
        $archive = new Archive(__DIR__ . '/test.tar');
        $this->assertInstanceOf('Archive_Tar', $archive->adapter()->archive());
        $archive->addFiles(__DIR__ . '/tmp');
        $this->assertContains('tmp/add.txt', $archive->listFiles()[0]);
        unlink(__DIR__ . '/test.tar');
    }

    public function testExtract()
    {
        touch(__DIR__ . '/tmp/test.txt');
        mkdir(__DIR__ . '/tmp/extract');
        $archive = new Archive(__DIR__ . '/tmp/test.tar');
        $archive->addFiles(__DIR__ . '/tmp/test.txt');
        $archive->extract(__DIR__ . '/tmp/extract');
        $this->assertEquals(realpath(__DIR__ . '/tmp/test.txt'), $archive->adapter()->listFiles(true)[0]['filename']);
        $this->assertGreaterThan(2, count(scandir(__DIR__ . '/tmp/extract')));
        unlink(__DIR__ . '/tmp/test.txt');
        unlink(__DIR__ . '/tmp/test.tar');
        $this->emptyDir(true, __DIR__ . '/tmp/extract');
    }

    protected function emptyDir($remove = false, $path = null)
    {
        if (null === $path) {
            $path = $this->path;
        }
        if (!$dh = @opendir($path)) {
            return;
        }

        while (false !== ($obj = readdir($dh))) {
            if ($obj == '.' || $obj == '..') {
                continue;
            }
            if (!@unlink($path . DIRECTORY_SEPARATOR . $obj)) {
                $this->emptyDir(true, $path . DIRECTORY_SEPARATOR . $obj);
            }
        }

        closedir($dh);

        if ($remove) {
            @rmdir($path);
        }
    }

}