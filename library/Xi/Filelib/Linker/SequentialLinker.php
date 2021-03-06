<?php

/**
 * This file is part of the Xi Filelib package.
 *
 * For copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Xi\Filelib\Linker;

use Xi\Filelib\Linker\AbstractLinker;
use Xi\Filelib\Linker\Linker;
use Xi\Filelib\File\File;
use Xi\Filelib\Plugin\VersionProvider\VersionProvider;
use Xi\Filelib\Exception\InvalidArgumentException;

/**
 * Sequential linker creates a sequential link with n levels of directories with m files per directory
 *
 * @author pekkis
 * @author Petri Mahanen
 */
class SequentialLinker extends AbstractLinker implements Linker
{
    /**
     * @var integer Files per directory
     */
    private $filesPerDirectory = 500;

    /**
     * @var integer Levels in directory structure
     */
    private $directoryLevels = 1;

    /**
     * Sets files per directory
     *
     * @param  integer          $filesPerDirectory
     * @return SequentialLinker
     */
    public function setFilesPerDirectory($filesPerDirectory)
    {
        $this->filesPerDirectory = $filesPerDirectory;
        return $this;
    }

    /**
     * Returns files per directory
     *
     * @return integer
     */
    public function getFilesPerDirectory()
    {
        return $this->filesPerDirectory;
    }

    /**
     * Sets levels per directory hierarchy
     *
     * @param  integer          $directoryLevels
     * @return SequentialLinker
     */
    public function setDirectoryLevels($directoryLevels)
    {
        $this->directoryLevels = $directoryLevels;
        return $this;
    }

    /**
     * Returns levels in directory hierarchy
     *
     * @return integer
     */
    public function getDirectoryLevels()
    {
        return $this->directoryLevels;
    }

    /**
     * Returns directory path for specified file id
     *
     * @param  File   $file
     * @return string
     */
    public function getDirectoryId(File $file)
    {
        return $this->calculateDirectoryId($file);
    }

    /**
     * Returns link for a version of a file
     *
     * @param  File   $file
     * @param  string $version   Version identifier
     * @param  string $extension Extension
     * @return string Versioned link
     */
    public function getLinkVersion(File $file, $version, $extension)
    {

        $link = $this->getLink($file);
        $pinfo = pathinfo($link);
        $link = $pinfo['dirname'] . '/' . $pinfo['filename'] . '-' . $version;
        $link .= '.' . $extension;

        return $link;
    }

    /**
     * Returns a link for a file
     *
     * @param  File   $file
     * @return string Link
     */
    public function getLink(File $file)
    {
        $url = array();
        $url[] = $this->getDirectoryId($file);
        $name = $file->getName();
        $url[] = $name;
        $url = implode(DIRECTORY_SEPARATOR, $url);

        return $url;
    }

    private function calculateDirectoryId(File $file)
    {
        if(!is_numeric($file->getId())) {
            throw new InvalidArgumentException("Leveled linker requires numeric file ids ('{$file->getId()}' was provided)");
        }

        if($this->getDirectoryLevels() < 1) {
            throw new InvalidArgumentException("Invalid number of directory levels ({$this->getDirectoryLevels()})");
        }


        $fileId = $file->getId();

        $directoryLevels = $this->getDirectoryLevels() + 1;
        $filesPerDirectory = $this->getFilesPerDirectory();

        $arr = array();
        $tmpfileid = $fileId - 1;

        for($count = 1; $count <= $directoryLevels; ++$count) {
            $lus = $tmpfileid / pow($filesPerDirectory, $directoryLevels - $count);
            $tmpfileid = $tmpfileid % pow($filesPerDirectory, $directoryLevels - $count);
            $arr[] = floor($lus) + 1;
        }

        $puuppa = array_pop($arr);
        return implode(DIRECTORY_SEPARATOR, $arr);

    }
}
