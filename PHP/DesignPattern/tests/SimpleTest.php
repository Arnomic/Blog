<?
use PHPUnit\Framework\TestCase;

class SimpleTest extends TestCase
{
    public function testAny(){
        $this->assertEquals(0,(int)true);
    }
}