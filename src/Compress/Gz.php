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
namespace Pop\Archive\Compress;

/**
 * Archive compress Gz class
 *
 * @category   Pop
 * @package    Pop_Archive
 * @author     Nick Sagona, III <dev@nolainteractive.com>
 * @copyright  Copyright (c) 2009-2016 NOLA Interactive, LLC. (http://www.nolainteractive.com)
 * @license    http://www.popphp.org/license     New BSD License
 * @version    2.1.1
 */
class Gz implements CompressInterface
{

    /**
     * Compress a file with gzip
     *
     * @param  string $file
     * @param  int    $level
     * @param  int    $mode
     * @return mixed
     */
    public static function compress($file, $level = 9, $mode = FORCE_GZIP)
    {
        $fullpath = realpath($file);
        $file     = file_get_contents($file);

        // Create the new Gzip file resource, write data and close it
        $gzResource = fopen($fullpath . '.gz', 'w');
        fwrite($gzResource, gzencode($file, $level, $mode));
        fclose($gzResource);

        return $fullpath . '.gz';
    }

    /**
     * Decompress a file with gzip
     *
     * @param  string $file
     * @return mixed
     */
    public static function decompress($file)
    {
        $gz = gzopen($file, 'r');
        $uncompressed = '';

        // Read the uncompressed data
        while (!feof($gz)) {
            $uncompressed .= gzread($gz, 4096);
        }

        // Close the Gzip compressed file and write
        // the data to the uncompressed file
        gzclose($gz);
        $newFile = (stripos($file, '.tgz') !== false)
            ? str_replace('.tgz', '.tar', $file) : str_replace('.gz', '', $file);

        file_put_contents($newFile, $uncompressed);

        return $newFile;
    }

}
