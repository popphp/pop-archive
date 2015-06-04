<?php

namespace Pop\Archive\Test;

use Pop\Archive\Compress;

class CompressTest extends \PHPUnit_Framework_TestCase
{

    public function testCompressAndUnCompressGz()
    {
        touch(__DIR__ . '/tmp/test.txt');
        file_put_contents(__DIR__ . '/tmp/test.txt', 'Hello World!');
        Compress\Gz::compress(__DIR__ . '/tmp/test.txt');
        unlink(__DIR__ . '/tmp/test.txt');
        $this->assertFileExists(__DIR__ . '/tmp/test.txt.gz');
        $this->assertFileNotExists(__DIR__ . '/tmp/test.txt');
        Compress\Gz::decompress(__DIR__ . '/tmp/test.txt.gz');
        $this->assertFileExists(__DIR__ . '/tmp/test.txt');
        $this->assertEquals('Hello World!', file_get_contents(__DIR__ . '/tmp/test.txt'));
        unlink(__DIR__ . '/tmp/test.txt');
        unlink(__DIR__ . '/tmp/test.txt.gz');
    }

    public function testCompressAndUnCompressBz()
    {
        touch(__DIR__ . '/tmp/test.txt');
        file_put_contents(__DIR__ . '/tmp/test.txt', 'Hello World!');
        Compress\Bz2::compress(__DIR__ . '/tmp/test.txt');
        unlink(__DIR__ . '/tmp/test.txt');
        $this->assertFileExists(__DIR__ . '/tmp/test.txt.bz2');
        $this->assertFileNotExists(__DIR__ . '/tmp/test.txt');
        Compress\Bz2::decompress(__DIR__ . '/tmp/test.txt.bz2');
        $this->assertFileExists(__DIR__ . '/tmp/test.txt');
        $this->assertEquals('Hello World!', file_get_contents(__DIR__ . '/tmp/test.txt'));
        unlink(__DIR__ . '/tmp/test.txt');
        unlink(__DIR__ . '/tmp/test.txt.bz2');
    }

    public function testCompressAndUnCompressTbz()
    {
        touch(__DIR__ . '/tmp/test.txt');
        file_put_contents(__DIR__ . '/tmp/test.txt', 'Hello World!');
        Compress\Bz2::compress(__DIR__ . '/tmp/test.txt');
        unlink(__DIR__ . '/tmp/test.txt');
        rename(__DIR__ . '/tmp/test.txt.bz2', __DIR__ . '/tmp/test.txt.tbz');
        $this->assertFileExists(__DIR__ . '/tmp/test.txt.tbz');
        $this->assertFileNotExists(__DIR__ . '/tmp/test.txt');
        Compress\Bz2::decompress(__DIR__ . '/tmp/test.txt.tbz');
        $this->assertFileExists(__DIR__ . '/tmp/test.txt.tar');
        $this->assertEquals('Hello World!', file_get_contents(__DIR__ . '/tmp/test.txt.tar'));
        unlink(__DIR__ . '/tmp/test.txt.tar');
        unlink(__DIR__ . '/tmp/test.txt.tbz');
    }

    public function testCompressAndUnCompressTbz2()
    {
        touch(__DIR__ . '/tmp/test.txt');
        file_put_contents(__DIR__ . '/tmp/test.txt', 'Hello World!');
        Compress\Bz2::compress(__DIR__ . '/tmp/test.txt');
        unlink(__DIR__ . '/tmp/test.txt');
        rename(__DIR__ . '/tmp/test.txt.bz2', __DIR__ . '/tmp/test.txt.tbz2');
        $this->assertFileExists(__DIR__ . '/tmp/test.txt.tbz2');
        $this->assertFileNotExists(__DIR__ . '/tmp/test.txt');
        Compress\Bz2::decompress(__DIR__ . '/tmp/test.txt.tbz2');
        $this->assertFileExists(__DIR__ . '/tmp/test.txt.tar');
        $this->assertEquals('Hello World!', file_get_contents(__DIR__ . '/tmp/test.txt.tar'));
        unlink(__DIR__ . '/tmp/test.txt.tar');
        unlink(__DIR__ . '/tmp/test.txt.tbz2');
    }

}