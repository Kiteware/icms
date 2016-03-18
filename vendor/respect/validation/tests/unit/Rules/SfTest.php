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

use Respect\Validation\Validator as v;

class SfTest extends \PHPUnit_Framework_TestCase
{
    public function testValidationWithAnExistingValidationConstraint()
    {
        $constraintName = 'Time';
        $validConstraintVal = '04:20:00';
        $invalidConstraintVal = 'yada';
        $this->assertTrue(
            v::sf($constraintName)->validate($validConstraintVal),
            sprintf('"%s" should be valid under "%s" constraint.', $validConstraintVal, $constraintName)
        );
        $this->assertFalse(
            v::sf($constraintName)->validate($invalidConstraintVal),
            sprintf('"%s" should be invalid under "%s" constraint.', $invalidConstraintVal, $constraintName)
        );
    }

    /**
     * @depends testValidationWithAnExistingValidationConstraint
     */
    public function testAssertionWithAnExistingValidationConstraint()
    {
        $constraintName = 'Time';
        $validConstraintVal = '04:20:00';
        $this->assertTrue(
            v::sf($constraintName)->assert($validConstraintVal),
            sprintf('"%s" should be valid under "%s" constraint.', $validConstraintVal, $constraintName)
        );
    }

    /**
     * @depends testAssertionWithAnExistingValidationConstraint
     */
    public function testAssertionMessageWithAnExistingValidationConstraint()
    {
        $constraintName = 'Time';
        $invalidConstraintVal = '34:90:70';
        try {
            v::sf($constraintName)->assert($invalidConstraintVal);
        } catch (\Respect\Validation\Exceptions\AllOfException $exception) {
            $fullValidationMessage = $exception->getFullMessage();
            $expectedValidationException = <<<EOF
\-These rules must pass for "34:90:70"
  \-Time
EOF;

            return $this->assertEquals(
                $expectedValidationException,
                $fullValidationMessage,
                'Exception message is different from the one expected.'
            );
        }
        $this->fail('Validation exception expected to compare message.');
    }

    /**
     * @expectedException Respect\Validation\Exceptions\ComponentException
     * @expectedExceptionMessage Symfony/Validator constraint "FluxCapacitor" does not exist.
     */
    public function testValidationWithNonExistingConstraint()
    {
        $fantasyConstraintName = 'FluxCapacitor';
        $fantasyValue = '8GW';
        v::sf($fantasyConstraintName)->validate($fantasyValue);
    }
}
