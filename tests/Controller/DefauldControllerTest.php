<?php

namespace tests\Controller;

use App\Tests\Math;
use App\Tests\MyException;
use PHPUnit\Framework\Exception;
use PHPUnit\Framework\TestCase;

class DefauldControllerTest extends  TestCase
{
    protected $math;

    public function setUp(): void
    {
        $this->math = new Math();

    }
//    public function providerSq()
//    {
//        return [
//          [4,16],
//          [5,25],
//          [6,36],
//          [7,49],
//        ];
//    }
//    /**
//     * @dataProvider providerSq
//     */
//    public function testSq($a, $b)
//    {
//        $math = new Math();
//        $this->assertEquals($a, $math->sqrt($b));
//    }

//    /**
//     * @dataProvider providerSq
//     */
//    public function testTrue($a, $b)
//    {
//        $math = new Math();
//        $this->assertTrue($math->true($b));
//        $this->assertFalse($math->true($b));
//    }

//    public function providerGetArray()
//    {
//        return [
//          [3,"hello|world|str"],
//          [1,"hello1|world1|str1"],
//          [4,"hello2|world2|str2"],
//          [3,"hello3|world3|str3"],
//        ];
//    }

//    /**
//     * @dataProvider providerGetArray
//     */
//    public function testGetArray($key, $str)
//    {
//        $math = new Math();
//        $this->assertArrayHasKey($key,$math->getArray($str));
//        $this->assertContains($key,$math->getArray($str));
//        $this->assertContainsOnly('string',$math->getArray($str));
//        $this->assertCount($key,$math->getArray($str));
//    }

    public function providerGetEx()
    {
        return [
          [3],
          [1],
          [10],
          [3],
        ];
    }

    /**
     * @dataProvider providerGetEx
     * @expectedException MyException
     */
    public function testGetEx($key)
    {
        $math = new Math();
        $math->getEx($key);
    }

    protected function tearDown(): void
    {
        $this->math = null;
    }
}
