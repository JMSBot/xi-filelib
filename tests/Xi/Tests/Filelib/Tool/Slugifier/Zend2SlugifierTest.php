<?php

namespace Xi\Tests\Filelib\Tool\Slugifier;

use Xi\Filelib\Tool\Slugifier\Zend2Slugifier;

class Zend2SlugifierTest extends TestCase
{
    public function setUp()
    {
        if (!class_exists('Zend\Filter\FilterChain')) {
            $this->markTestSkipped('Zend Framework 2 filters not loadable');
        }

        if (!extension_loaded('intl')) {
            $this->markTestSkipped('Intl extension must be loaded');
        }

        $trans = $this->getMock('Xi\Filelib\Tool\Transliterator\Transliterator');
        $trans->expects($this->any())->method('transliterate')->will($this->returnArgument(0));
        $this->slugifier = new Zend2Slugifier($trans);
    }

    
    /**
     * @test
     */
    public function classShouldExist()
    {
        $this->assertTrue(class_exists('Xi\Filelib\Tool\Slugifier\Zend2Slugifier'));
        $this->assertContains('Xi\Filelib\Tool\Slugifier\AbstractZendSlugifier', class_parents('Xi\Filelib\Tool\Slugifier\Zend2Slugifier'));
        $this->assertContains('Xi\Filelib\Tool\Slugifier\Slugifier', class_implements('Xi\Filelib\Tool\Slugifier\Zend2Slugifier'));
    }
    
   /**
     * @test
     */
    public function getFilterShouldReturnAnInstanceOfZendFilterChainAndCacheItsResult()
    {

        $slugifier = new Zend2Slugifier($this->getMock('Xi\Filelib\Tool\Transliterator\Transliterator'));
        $filter = $slugifier->getFilter();
        
        $this->assertInstanceOf('Zend\Filter\FilterChain', $filter);
        
        $filter2 = $slugifier->getFilter();
        
        $this->assertSame($filter, $filter2);
        
    }


}
