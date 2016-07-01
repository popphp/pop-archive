<?php
/**
 * Pop PHP Framework (http://www.popphp.org/)
 *
 * @link       https://github.com/popphp/popphp-framework
 * @author     Nick Sagona, III <dev@nolainteractive.com>
 * @copyright  Copyright (c) 2009-2016 NOLA Interactive, LLC. (http://www.nolainteractive.com)
 * @license    http://www.popphp.org/license     New BSD License
 */

/**
 * @namespace
 */
namespace Pop\Archive;

/**
 * Archive class
 *
 * @category   Pop
 * @package    Pop_Archive
 * @author     Nick Sagona, III <dev@nolainteractive.com>
 * @copyright  Copyright (c) 2009-2016 NOLA Interactive, LLC. (http://www.nolainteractive.com)
 * @license    http://www.popphp.org/license     New BSD License
 * @version    2.1.0
 */
class Archive
{

    /**
     * Array of allowed file types.
     * @var array
     */
    protected $allowed = [
        'bz2'  => 'application/bzip2',
        'gz'   => 'application/x-gzip',
        'rar'  => 'application/x-rar-compressed',
        'tar'  => 'application/x-tar',
        'tbz'  => 'application/bzip2',
        'tbz2' => 'application/bzip2',
        'tgz'  => 'application/x-gzip',
        'zip'  => 'application/x-zip'
    ];

    /**
     * Archive adapter
     * @var mixed
     */
    protected $adapter = null;

    /**
     * Full path of archive, i.e. '/path/to/archive.ext'
     * @var string
     */
    protected $fullpath = null;

    /**
     * Full basename of archive, i.e. 'archive.ext'
     * @var string
     */
    protected $basename = null;

    /**
     * Full filename of archive, i.e. 'archive'
     * @var string
     */
    protected $filename = null;

    /**
     * Archive extension, i.e. 'ext'
     * @var string
     */
    protected $extension = null;

    /**
     * Archive size in bytes
     * @var int
     */
    protected $size = 0;

    /**
     * Archive mime type
     * @var string
     */
    protected $mime = null;

    /**
     * Constructor
     *
     * Instantiate the archive object
     *
     * @param  string $archive
     * @param  string $password
     * @param  string $prefix
     * @throws Exception
     * @return Archive
     */
    public function __construct($archive, $password = null, $prefix = 'Pop\\Archive\\Adapter\\')
    {
        $this->allowed   = self::getFormats();
        $this->fullpath  = $archive;
        $parts           = pathinfo($archive);
        $this->size      = (file_exists($archive)) ? filesize($archive) : 0;
        $this->basename  = $parts['basename'];
        $this->filename  = $parts['filename'];
        $this->extension = (isset($parts['extension']) && ($parts['extension'] != '')) ? $parts['extension'] : null;

        if (null === $this->extension) {
            throw new Exception('Error: Unable able to detect archive extension or mime type.');
        }
        if (!isset($this->allowed[strtolower($this->extension)])) {
            throw new Exception('Error: That archive type is not allowed.');
        }

        $this->mime = $this->allowed[strtolower($this->extension)];
        $this->setAdapter($prefix, $password);
    }

    /**
     * Get formats
     *
     * @return array
     */
    public static function getFormats()
    {
        $allowed = [
            'bz2'  => 'application/bzip2',
            'gz'   => 'application/x-gzip',
            'rar'  => 'application/x-rar-compressed',
            'tar'  => 'application/x-tar',
            'tbz'  => 'application/bzip2',
            'tbz2' => 'application/bzip2',
            'tgz'  => 'application/x-gzip',
            'zip'  => 'application/x-zip'
        ];

        // Check if Bzip2 is available.
        if (!function_exists('bzcompress')) {
            unset($allowed['bz2']);
            unset($allowed['tbz']);
            unset($allowed['tbz2']);
        }
        // Check if Gzip is available.
        if (!function_exists('gzcompress')) {
            unset($allowed['gz']);
            unset($allowed['tgz']);
        }
        // Check if Rar is available.
        if (!class_exists('RarArchive', false)) {
            unset($allowed['rar']);
        }

        // Check if Tar is available.
        if (!class_exists('Archive_Tar')) {
            unset($allowed['bz']);
            unset($allowed['bz2']);
            unset($allowed['gz']);
            unset($allowed['tar']);
            unset($allowed['tgz']);
            unset($allowed['tbz']);
            unset($allowed['tbz2']);
        }

        // Check if Zip is available.
        if (!class_exists('ZipArchive', false)) {
            unset($allowed['zip']);
        }

        return $allowed;
    }

    /**
     * Return the fullpath
     *
     * @return string
     */
    public function getFullPath()
    {
        return $this->fullpath;
    }

    /**
     * Return the basename
     *
     * @return string
     */
    public function getBasename()
    {
        return $this->basename;
    }

    /**
     * Return the filename
     *
     * @return string
     */
    public function getFilename()
    {
        return $this->filename;
    }

    /**
     * Return the extension
     *
     * @return string
     */
    public function getExtension()
    {
        return $this->extension;
    }

    /**
     * Return the size
     *
     * @return int
     */
    public function getSize()
    {
        return $this->size;
    }

    /**
     * Return the mime
     *
     * @return string
     */
    public function getMime()
    {
        return $this->mime;
    }

    /**
     * Return the adapter object
     *
     * @return mixed
     */
    public function adapter()
    {
        return $this->adapter;
    }

    /**
     * Return the archive object within the adapter object
     *
     * @return mixed
     */
    public function archive()
    {
        return $this->adapter->archive();
    }

    /**
     * Extract an archived and/or compressed file
     *
     * @param  string $to
     * @throws Exception
     * @return Archive
     */
    public function extract($to = null)
    {
        if (null === $to) {
            $to = getcwd();
        }

        if (!is_writable($to)) {
            throw new Exception('Error: Extract directory is not writable.');
        }

        $this->adapter->extract($to);
        return $this;
    }

    /**
     * Create an archive file
     *
     * @param  string|array $files
     * @return Archive
     */
    public function addFiles($files)
    {
        $this->adapter->addFiles($files);
        $this->size = filesize($this->fullpath);
        return $this;
    }

    /**
     * Return a listing of the contents of an archived file
     *
     * @param  boolean $full
     * @return array
     */
    public function listFiles($full = false)
    {
        return $this->adapter->listFiles($full);
    }

    /**
     * Compress an archive file with Gzip or Bzip2
     *
     * @param  string $ext
     * @return Archive
     */
    public function compress($ext = 'gz')
    {
        if ($ext == 'bz') {
            $ext .= '2';
        }
        switch ($ext) {
            case 'gz':
                $newArchive = Compress\Gz::compress($this->fullpath);
                break;
            case 'tgz':
                $tmpArchive = Compress\Gz::compress($this->fullpath);
                $newArchive = str_replace('.tar.gz', '.tgz', $tmpArchive);
                rename($tmpArchive, $newArchive);
                break;
            case 'bz2':
                $newArchive = Compress\Bz2::compress($this->fullpath);
                break;
            case 'tbz':
                $tmpArchive = Compress\Bz2::compress($this->fullpath);
                $newArchive = str_replace('.tar.bz2', '.tbz', $tmpArchive);
                rename($tmpArchive, $newArchive);
                break;
            case 'tbz2':
                $tmpArchive = Compress\Bz2::compress($this->fullpath);
                $newArchive = str_replace('.tar.bz2', '.tbz2', $tmpArchive);
                rename($tmpArchive, $newArchive);
                break;
            default:
                $newArchive = $this->fullpath;
        }

        if (file_exists($this->fullpath)) {
            unlink($this->fullpath);
        }

        // Reset values for newly compressed archive file.
        $this->fullpath  = $newArchive;
        $parts           = pathinfo($newArchive);
        $this->size      = (file_exists($newArchive)) ? filesize($newArchive) : 0;
        $this->basename  = $parts['basename'];
        $this->filename  = $parts['filename'];
        $this->extension = (isset($parts['extension']) && ($parts['extension'] != '')) ? $parts['extension'] : null;
        $this->mime      = $this->allowed[$this->extension];

        return $this;
    }


    /**
     * Set the adapter based on the file name
     *
     * @param  string $prefix
     * @param  string $password
     * @throws Exception
     * @return void
     */
    protected function setAdapter($prefix, $password = null)
    {
        $ext = strtolower($this->extension);

        if (($ext == 'tar') || (stripos($ext, 'bz') !== false) || (stripos($ext, 'gz') !== false)) {
            $class = $prefix . 'Tar';
        } else {
            $class = $prefix . ucfirst($ext);
        }

        $this->adapter = (null !== $password) ? new $class($this, $password) : new $class($this);

        if (!($this->adapter instanceof Adapter\ArchiveInterface)) {
            throw new Exception('Error: The archive adapter must implement Adapter\\ArchiveInterface');
        }
    }

}
