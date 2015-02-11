<?php
/**
 * Pop PHP Framework (http://www.popphp.org/)
 *
 * @link       https://github.com/popphp/popphp
 * @category   Pop
 * @package    Pop_Archive
 * @author     Nick Sagona, III <dev@nolainteractive.com>
 * @copyright  Copyright (c) 2009-2015 NOLA Interactive, LLC. (http://www.nolainteractive.com)
 * @license    http://www.popphp.org/license     New BSD License
 */

/**
 * @namespace
 */
namespace Pop\Archive\Compress;

/**
 * Archive compress Bz2 class
 *
 * @category   Pop
 * @package    Pop_Archive
 * @author     Nick Sagona, III <dev@nolainteractive.com>
 * @copyright  Copyright (c) 2009-2015 NOLA Interactive, LLC. (http://www.nolainteractive.com)
 * @license    http://www.popphp.org/license     New BSD License
 * @version    2.0.0a
 */
class Bz2 implements CompressInterface
{

    /**
     * Compress a file with bzip2
     *
     * @param  string $file
     * @return mixed
     */
    public static function compress($file)
    {
        $fullpath = realpath($file);
        $file     = file_get_contents($file);

        // Create the new Bzip2 file resource, write data and close it
        $bzResource = bzopen($fullpath . '.bz2', 'w');
        bzwrite($bzResource, $file, strlen($file));
        bzclose($bzResource);

        return $fullpath . '.bz2';
    }

    /**
     * Decompress a file with bzip2
     *
     * @param  string $file
     * @return mixed
     */
    public static function decompress($file)
    {
        $bz = bzopen($file, 'r');
        $uncompressed = '';

        // Read the uncompressed data.
        while (!feof($bz)) {
            $uncompressed .= bzread($bz, 4096);
        }

        // Close the Bzip2 compressed file and write
        // the data to the uncompressed file.
        bzclose($bz);
        if (stripos($file, '.tbz2') !== false) {
            $newFile = str_replace('.tbz2', '.tar', $file);
        } else if (stripos($file, '.tbz') !== false) {
            $newFile = str_replace('.tbz', '.tar', $file);
        } else {
            $newFile = str_replace('.bz2', '', $file);
        }

        file_put_contents($newFile, $uncompressed);

        return $newFile;
    }

}
