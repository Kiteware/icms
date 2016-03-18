<?php

/*
 * This file is part of Respect/Validation.
 *
 * (c) Alexandre Gomes Gaigalas <alexandre@gaigalas.net>
 *
 * For the full copyright and license information, please view the "LICENSE.md"
 * file that was distributed with this source code.
 */

namespace Respect\Validation\Rules;

class FloatTypeTest extends \PHPUnit_Framework_TestCase
{
    protected $floatValidator;

    protected function setUp()
    {
        $this->floatValidator = new FloatType();
    }

    /**
     * @dataProvider providerForFloatType
     */
    public function testFloatTypeNumbersShouldPass($input)
    {
        $this->assertTrue($this->floatValidator->assert($input));
        $this->assertTrue($this->floatValidator->__invoke($input));
        $this->assertTrue($this->floatValidator->check($input));
    }

    /**
     * @dataProvider providerForNotFloatType
     * @expectedException Respect\Validation\Exceptions\FloatTypeException
     */
    public function testNotFloatTypeNumbersShouldFail($input)
    {
        $this->assertFalse($this->floatValidator->__invoke($input));
        $this->assertFalse($this->floatValidator->assert($input));
    }

    public function providerForFloatType()
    {
        return array(
            array(''),
            array(165),
            array(1),
            array(0),
            array(0.0),
            array('1'),
            array('19347e12'),
            array(165.0),
            array('165.7'),
            array(1e12),
        );
    }

    public function providerForNotFloatType()
    {
        return array(
            array(null),
            array('a'),
            array(' '),
            array('Foo'),
        );
    }
}
