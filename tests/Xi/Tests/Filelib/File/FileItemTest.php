<?php

namespace Xi\Tests\Filelib\File;

use Xi\Filelib\FileLibrary,
    DateTime,
    Xi\Filelib\File\FileItem;

class FileItemTest extends \PHPUnit_Framework_TestCase
{
    
    /**
     * @test
     */
    public function classShouldExist()
    {
        $this->assertTrue(class_exists('Xi\Filelib\File\FileItem'));
        $this->assertContains('Xi\Filelib\File\File', class_implements('Xi\Filelib\File\FileItem'));
    }
    
    
    /**
     * @test
     */
    public function gettersAndSettersShouldWorkAsExpected()
    {
        $file = new FileItem();
        
        $filelib = $this->getMock('Xi\Filelib\FileLibrary');
        $this->assertEquals(null, $file->getFilelib());
        $this->assertSame($file, $file->setFilelib($filelib));
        $this->assertSame($filelib, $file->getFilelib());
        
        $val = 666;
        $this->assertEquals(null, $file->getId());
        $this->assertSame($file, $file->setId($val));
        $this->assertEquals($val, $file->getId());

        $val = 'image/lus';
        $this->assertEquals(null, $file->getFolderId());
        $this->assertSame($file, $file->setFolderId($val));
        $this->assertEquals($val, $file->getFolderId());

        $val = 'image/lus';
        $this->assertEquals(null, $file->getMimetype());
        $this->assertSame($file, $file->setMimetype($val));
        $this->assertEquals($val, $file->getMimetype());

        $val = 'lamanmeister';
        $this->assertEquals(null, $file->getProfile());
        $this->assertSame($file, $file->setProfile($val));
        $this->assertEquals($val, $file->getProfile());

        $val = 64643;
        $this->assertEquals(null, $file->getSize());
        $this->assertSame($file, $file->setSize($val));
        $this->assertEquals($val, $file->getSize());

        $val = 'lamanmeister.xoo';
        $this->assertEquals(null, $file->getName());
        $this->assertSame($file, $file->setName($val));
        $this->assertEquals($val, $file->getName());

        $val = 'linkster';
        $this->assertEquals(null, $file->getLink());
        $this->assertSame($file, $file->setLink($val));
        $this->assertEquals($val, $file->getLink());

        $val = new DateTime('1978-01-02');
        $this->assertEquals(null, $file->getDateUploaded());
        $this->assertSame($file, $file->setDateUploaded($val));
        $this->assertSame($val, $file->getDateUploaded());
        
    }

    /**
     * @test
     */
    public function getProfileObjectShouldDelegateToFileOperator()
    {
                
        $filelib = new FileLibrary();
        
        $fiop = $this->getMock('Xi\Filelib\File\FileOperator');
        $fiop->expects($this->once())->method('getProfile')->with($this->equalTo('lussmeister'));
        
        $filelib->setFileOperator($fiop);
                
        $file = new FileItem();
        $file->setFilelib($filelib);
        $file->setProfile('lussmeister');
        
        $file->getProfileObject();
        
    }
    
    public function fromArrayProvider()
    {
        return array(
            array(
                array(
                    'id' => 1,
                    'folder_id' => 1,
                    'mimetype' => 'image/jpeg',
                    'profile' => 'default',
                    'size' => 600,
                    'name' => 'puuppa.jpg',
                    'link' => 'lussenhoff',
                    'date_uploaded' => new \DateTime('2010-01-01 01:01:01'),
                ),         
            ),
            array(
                array(
                    'link' => 'lussenhoff',
                ),         
            ),
        
        );
        
        
    }
    
    /**
     * @dataProvider fromArrayProvider
     * @test
     */
    public function fromArrayShouldWorkAsExpected($data)
    {
        $file = new \Xi\Filelib\File\FileItem();
        $file->fromArray($data);

        $map = array(
            'id' => 'getId',
            'folder_id' => 'getFolderId',
            'mimetype' => 'getMimeType',
            'profile' => 'getProfile',
            'size' => 'getSize',
            'name' => 'getName',
            'link' => 'getLink',
            'date_uploaded' => 'getDateUploaded'
        );
        
        foreach($map as $key => $method) {
            if(isset($data[$key])) {
                $this->assertEquals($data[$key], $file->$method());    
            } else {
                $this->assertNull($file->$method());
            }
        }
        
    }
    
    /**
     * @test
     */
    public function toArrayShouldWorkAsExpected()
    {
        $file = new \Xi\Filelib\File\FileItem();
        $file->setId(1);
        $file->setFolderId(655);
        $file->setMimeType('tussi/lussutus');
        $file->setProfile('unknown');
        $file->setSize(123456);
        $file->setName('kukkuu.png');
        $file->setLink('linksor');
        $file->setDateUploaded(new \DateTime('1978-03-21'));
                
        $this->assertEquals($file->toArray(), array(
            'id' => 1,
            'folder_id' => 655,
            'mimetype' => 'tussi/lussutus',
            'profile' => 'unknown',
            'size' => 123456,
            'name' => 'kukkuu.png',
            'link' => 'linksor',
            'date_uploaded' => new \DateTime('1978-03-21')
        ));

        
        $file = new \Xi\Filelib\File\FileItem();
        $this->assertEquals($file->toArray(), array(
            'id' => null,
            'folder_id' => null,
            'mimetype' => null,
            'profile' => null,
            'size' => null,
            'name' => null,
            'link' => null,
            'date_uploaded' => null,
        ));
        
        
    }
    
    /**
     * @test
     */
    public function createShouldCreateNewInstance()
    {
        $this->assertInstanceOf('Xi\Filelib\File\FileItem', FileItem::create(array()));
    }
    
    
    
}