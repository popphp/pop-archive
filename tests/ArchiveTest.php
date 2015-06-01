<?php

namespace Pop\Archive\Test;

use Pop\Archive\Archive;

class ArchiveTest extends \PHPUnit_Framework_TestCase
{

    public function testConstructor()
    {
        $archive = new Archive('test.zip');
        $this->assertInstanceOf('Pop\Archive\Archive', $archive);
        $this->assertEquals('test', $archive->getFilename());
        $this->assertEquals('test.zip', $archive->getBasename());
        $this->assertEquals('zip', $archive->getExtension());
        $this->assertEquals(0, $archive->getSize());
        $this->assertEquals('application/x-zip', $archive->getMime());
        $this->assertInstanceOf('Pop\Archive\Adapter\Zip', $archive->adapter());
        $this->assertInstanceOf('ZipArchive', $archive->archive());
    }

    public function testNoExtensionException()
    {
        $this->setExpectedException('Pop\Archive\Exception');
        $archive = new Archive('badarchive');
    }

    public function testArchiveNotAllowedException()
    {
        $this->setExpectedException('Pop\Archive\Exception');
        $archive = new Archive('badarchive.bad');
    }

    public function testAddAndListFiles()
    {
        copy(__DIR__ . '/tmp/test.orig.zip', __DIR__ . '/tmp/test.zip');
        $archive = new Archive(__DIR__ . '/tmp/test.zip');
        $archive->addFiles(__DIR__ . '/tmp/add.txt');
        $this->assertContains('add.txt', $archive->listFiles());
    }

    public function testExtract()
    {
        $archive = new Archive(__DIR__ . '/tmp/test.zip');
        $archive->extract(__DIR__ . '/tmp');
        $this->assertFileExists(__DIR__ . '/tmp/test.txt');
        if (file_exists(__DIR__ . '/tmp/test.txt')) {
            unlink(__DIR__ . '/tmp/test.txt');
        }

        if (file_exists(__DIR__ . '/tmp/test.zip')) {
            unlink(__DIR__ . '/tmp/test.zip');
        }
    }

    public function testCompressGz()
    {
        $archive = new Archive(__DIR__ . '/tmp/test.tar');
        $archive->addFiles(__DIR__ . '/tmp/add.txt');
        $this->assertFileExists(__DIR__ . '/tmp/test.tar');
        $archive->compress('gz');
        $this->assertFileExists(__DIR__ . '/tmp/test.tar.gz');
        $this->assertEquals('test.tar', $archive->getFilename());
        $this->assertEquals('test.tar.gz', $archive->getBasename());
        $this->assertEquals('gz', $archive->getExtension());
        $this->assertEquals('application/x-gzip', $archive->getMime());
        if (file_exists(__DIR__ . '/tmp/test.tar.gz')) {
            unlink(__DIR__ . '/tmp/test.tar.gz');
        }
    }

    public function testCompressTgz()
    {
        $archive = new Archive(__DIR__ . '/tmp/test.tar');
        $archive->addFiles(__DIR__ . '/tmp/add.txt');
        $this->assertFileExists(__DIR__ . '/tmp/test.tar');
        $archive->compress('tgz');
        $this->assertFileExists(__DIR__ . '/tmp/test.tgz');
        $this->assertEquals('test', $archive->getFilename());
        $this->assertEquals('test.tgz', $archive->getBasename());
        $this->assertEquals('tgz', $archive->getExtension());
        $this->assertEquals('application/x-gzip', $archive->getMime());
        if (file_exists(__DIR__ . '/tmp/test.tgz')) {
            unlink(__DIR__ . '/tmp/test.tgz');
        }
    }

    public function testCompressBz2()
    {
        $archive = new Archive(__DIR__ . '/tmp/test.tar');
        $archive->addFiles(__DIR__ . '/tmp/add.txt');
        $this->assertFileExists(__DIR__ . '/tmp/test.tar');
        $archive->compress('bz');
        $this->assertFileExists(__DIR__ . '/tmp/test.tar.bz2');
        $this->assertEquals('test.tar', $archive->getFilename());
        $this->assertEquals('test.tar.bz2', $archive->getBasename());
        $this->assertEquals('bz2', $archive->getExtension());
        $this->assertEquals('application/bzip2', $archive->getMime());
        if (file_exists(__DIR__ . '/tmp/test.tar.bz2')) {
            unlink(__DIR__ . '/tmp/test.tar.bz2');
        }
    }

    public function testCompressTbz()
    {
        $archive = new Archive(__DIR__ . '/tmp/test.tar');
        $archive->addFiles(__DIR__ . '/tmp/add.txt');
        $this->assertFileExists(__DIR__ . '/tmp/test.tar');
        $archive->compress('tbz');
        $this->assertFileExists(__DIR__ . '/tmp/test.tbz');
        $this->assertEquals('test', $archive->getFilename());
        $this->assertEquals('test.tbz', $archive->getBasename());
        $this->assertEquals('tbz', $archive->getExtension());
        $this->assertEquals('application/bzip2', $archive->getMime());
        if (file_exists(__DIR__ . '/tmp/test.tbz')) {
            unlink(__DIR__ . '/tmp/test.tbz');
        }
    }

    public function testCompressTbz2()
    {
        $archive = new Archive(__DIR__ . '/tmp/test.tar');
        $archive->addFiles(__DIR__ . '/tmp/add.txt');
        $this->assertFileExists(__DIR__ . '/tmp/test.tar');
        $archive->compress('tbz2');
        $this->assertFileExists(__DIR__ . '/tmp/test.tbz2');
        $this->assertEquals('test', $archive->getFilename());
        $this->assertEquals('test.tbz2', $archive->getBasename());
        $this->assertEquals('tbz2', $archive->getExtension());
        $this->assertEquals('application/bzip2', $archive->getMime());
        if (file_exists(__DIR__ . '/tmp/test.tbz2')) {
            unlink(__DIR__ . '/tmp/test.tbz2');
        }
    }

}