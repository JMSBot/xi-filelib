<?php

namespace Xi\Tests\Filelib\Backend\Finder;

use Xi\Tests\Filelib\TestCase;
use Xi\Filelib\Backend\Finder\FileFinder;

class FileFinderTest extends TestCase
{
    /**
     * @test
     */
    public function classShouldExist()
    {
        $this->assertTrue(class_exists('Xi\Filelib\Backend\Finder\FileFinder'));
        $this->assertContains('Xi\Filelib\Backend\Finder\Finder', class_implements('Xi\Filelib\Backend\Finder\FileFinder'));
    }

    public function getExpectedFields()
    {
        return array(
            'id',
            'folder_id',
        );
    }

    public function getExpectedResultClass()
    {
        return 'Xi\Filelib\File\File';
    }


    public function setUp()
    {
        $this->finder = new FileFinder();
    }

}