<?php

namespace Xi\Filelib\Plugin;

use Xi\Filelib\File\Upload\FileUpload;

/**
 * Randomizes all uploads' file names before uploading. Ensures that same file may be uploaded
 * to the same directory time and again
 *
 * @author pekkis
 *
 */
class RandomizeNamePlugin extends AbstractPlugin
{

    /**
     * @var string Prefix (for uniqid)
     */
    protected $prefix = '';

    /**
     * Sets prefix
     *
     * @param $prefix
     */
    public function setPrefix($prefix)
    {
        $this->prefix = $prefix;
        return $this;
    }

    /**
     * Returns prefix
     *
     * @return string
     */
    public function getPrefix()
    {
        return $this->prefix;
    }

    public function beforeUpload(FileUpload $upload)
    {
        $pinfo = pathinfo($upload->getUploadFilename());
        $newname = uniqid($this->getPrefix(), true);

        $newname = str_replace('.', '_', $newname);

        if (isset($pinfo['extension'])) {
            $newname .= '.' . $pinfo['extension'];
        }

        $upload->setOverrideFilename($newname);
        return $upload;
    }

}

