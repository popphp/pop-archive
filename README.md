pop-archive
===========

[![Build Status](https://travis-ci.org/popphp/pop-archive.svg?branch=master)](https://travis-ci.org/popphp/pop-archive)
[![Coverage Status](http://www.popphp.org/cc/coverage.php?comp=pop-archive)](http://www.popphp.org/cc/pop-archive/)

OVERVIEW
--------
`pop-archive` provides a normalized interface and integrated adapters to let a user decompress,
extract, package and compress files in a common archive format. The supported formats are:

* tar
* tar.gz
* tar.bz2
* zip
* rar (extract-only)

`pop-archive` is a component of the [Pop PHP Framework](http://www.popphp.org/).

INSTALL
-------

Install `pop-archive` using Composer.

    composer require popphp/pop-archive

BASIC USAGE
-----------

### Extract a zip archive

```php
$archive = new Pop\Archive\Archive('test.zip');
$archive->extract('/path/to/extract/files');
```

### Extract a tar.gz archive

```php
// It will auto-detect and automatically decompress a compressed TAR file 
$archive = new Pop\Archive\Archive('test.tar.gz');
$archive->extract('/path/to/extract/files');
```

### Add files to a zip archive

```php
$archive = new Pop\Archive\Archive('test.zip');
$archive->addFiles('/path/to/single/file.txt');
$archive->addFiles([
    '/path/to/multiple/files1.txt',
    '/path/to/multiple/files2.txt',
    '/path/to/multiple/files3.txt',
]);
```

### Add files to a tar archive and compress

```php
$archive = new Pop\Archive\Archive('test.tar');
$archive->addFiles('/path/to/folder/of/files');

// Creates the compressed archive file 'test.tar.bz2'
$archive->compress('bz2');
```
