<?php

namespace Xi\Tests\Filelib\Storage;

use Xi\Filelib\Storage\AmazonS3Storage;
use Xi\Filelib\File\Resource;

class AmazonS3StorageTest extends \Xi\Tests\Filelib\TestCase
{

    /**
     *
     * @var AmazonS3Storage
     */
    protected $storage;

    protected $resource;

    protected $versionProvider;

    protected $fileResource;

    protected $filelib;


    public function setUp()
    {

        if (!class_exists('Zend\Service\Amazon\S3\S3')) {
            $this->markTestSkipped('Zend_Service_Amazon_S3 class could not be loaded');
        }

        if (!S3_KEY) {
            $this->markTestSkipped('S3 not configured');
        }


        $this->fileResource = realpath(ROOT_TESTS . '/data') . '/self-lussing-manatee.jpg';

        $this->filelib = $this->getFilelib();

        $storage = new AmazonS3Storage();
        $storage->setKey(S3_KEY);
        $storage->setSecretKey(S3_SECRETKEY);
        $storage->setBucket(S3_BUCKET);


        $this->storage = $storage;

        $dc = $this->getMock('Xi\Filelib\Storage\Filesystem\DirectoryIdCalculator\DirectoryIdCalculator');
        $dc->expects($this->any())
            ->method('calculateDirectoryId')
            ->will($this->returnValue('1'));

        $this->version = 'xoo';

        $this->resource = Resource::create(array('id' => 1));
    }

    public function tearDown()
    {
        if (!class_exists('Zend\Service\Amazon\S3\S3')) {
            return;
        }

        if (!S3_KEY) {
            $this->markTestSkipped('S3 not configured');
        }

        $this->storage->getAmazonService()->cleanBucket($this->storage->getBucket());

    }


    /**
     * @test
     */
    public function storeAndRetrieveAndDeleteShouldWorkSeamlessly()
    {
        $this->storage->setFilelib($this->getFilelib());
        $this->storage->store($this->resource, $this->fileResource);

        $retrieved = $this->storage->retrieve($this->resource);

        $this->assertInstanceof('Xi\Filelib\File\FileObject', $retrieved);

        $this->assertFileEquals($this->fileResource, $retrieved->getRealPath());

        $this->storage->delete($this->resource);

        $ret = $this->storage->getAmazonService()->isObjectAvailable($this->storage->getPath($this->resource));

        $this->assertFalse($ret);

        $this->assertFileEquals($this->fileResource, $retrieved->getRealPath());

    }

    /**
     * @test
     */
    public function destructorShouldCleanUpTheStoragesMess()
    {
        $storage = new AmazonS3Storage();
        $storage->setFilelib($this->getFilelib());
        $storage->setKey(S3_KEY);
        $storage->setSecretKey(S3_SECRETKEY);
        $storage->setBucket(S3_BUCKET);

        $storage->store($this->resource, $this->fileResource);

        $retrieved = $storage->retrieve($this->resource);
        $this->assertInstanceof('Xi\Filelib\File\FileObject', $retrieved);

        $retrievedPath = $retrieved->getRealPath();

        unset($storage);

        $this->assertFileNotExists($retrievedPath);

    }

    /**
     * @test
     */
    public function storeAndRetrieveAndDeleteVersionShouldWorkSeamlessly()
    {
        $this->storage->setFilelib($this->getFilelib());

        $this->storage->storeVersion($this->resource, $this->version, $this->fileResource);

        $retrieved = $this->storage->retrieveVersion($this->resource, $this->version);
        $this->assertInstanceof('Xi\Filelib\File\FileObject', $retrieved);

        $this->storage->deleteVersion($this->resource, $this->version);

        $ret = $this->storage->getAmazonService()->isObjectAvailable($this->storage->getPath($this->resource) . '_' . $this->version);
        $this->assertFalse($ret);

    }

    /**
     * @test
     */
    public function amazonServiceShouldCreateBucketIfItDoesNotExist()
    {
        $storage = new AmazonS3Storage();
        $storage->setFilelib($this->getFilelib());
        $storage->setKey(S3_KEY);
        $storage->setSecretKey(S3_SECRETKEY);
        $storage->setBucket(S3_BUCKET . 'lus');

        $storage->store($this->resource, $this->fileResource);

        $retrieved = $storage->retrieve($this->resource);
        $this->assertInstanceof('Xi\Filelib\File\FileObject', $retrieved);

        $storage->getAmazonService()->cleanBucket($storage->getBucket());
        $storage->getAmazonService()->removeBucket($storage->getBucket());
    }



}


