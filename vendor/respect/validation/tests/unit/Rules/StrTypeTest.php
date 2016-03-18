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

class StrTypeTest extends \PHPUnit_Framework_TestCase
{
    protected $object;

    protected function setUp()
    {
        $this->object = new StrType();
    }

    /**
     * @dataProvider providerForStrType
     */
    public function testStrType($input)
    {
        $this->assertTrue($this->object->validate($input));
        $this->assertTrue($this->object->check($input));
        $this->assertTrue($this->object->assert($input));
    }

    /**
     * @dataProvider providerForNotStrType
     * @expectedException Respect\Validation\Exceptions\StrTypeException
     */
    public function testNotStrType($input)
    {
        $this->assertFalse($this->object->validate($input));
        $this->assertFalse($this->object->assert($input));
    }

    public function providerForStrType()
    {
        return array(
            array(''),
            array('165.7'),
        );
    }

    public function providerForNotStrType()
    {
        return array(
            array(null),
            array(array()),
            array(new \stdClass()),
            array(150),
        );
    }
}
