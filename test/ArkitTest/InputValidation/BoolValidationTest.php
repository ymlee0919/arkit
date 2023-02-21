<?php

namespace ArkitTest\InputValidation;

use PHPUnit\Framework\TestCase;

class BoolValidationTest extends ValidationTest
{
    /**
     * @covers \Arkit\Core\Filter\Input\Validator\BoolValidator
     */
    public function testNotBoolean()
    {
        $list = [22, 'Hola', 45.54, [2,3,5]];
        foreach ($list as $value)
        {
            $valid = $this->validator->validate('value', $value)->isBoolean()->isValid();
            $this->assertFalse($valid, 'The value ' . json_encode($value) . ' is boolean');
        }
    }

    /**
     * @covers \Arkit\Core\Filter\Input\Validator\BoolValidator
     */
    public function testTrueValues()
    {
        $list = [1, true, 'On', 'on'];
        foreach ($list as $value)
        {
            $valid = $this->validator->validate('value', $value)->isBoolean()->isTrue()->isValid();
            $this->assertTrue($valid, 'The value ' . strval($value) . ' is not true');
        }
    }

    /**
     * @covers \Arkit\Core\Filter\Input\Validator\BoolValidator
     */
    public function testFalseValues()
    {
        $list = [0, false, 'Off', 'off'];
        foreach ($list as $value)
        {
            $valid = $this->validator->validate('value', $value)->isBoolean()->isFalse()->isValid();
            $this->assertTrue($valid, 'The value ' . strval($value) . ' is not false');
        }
    }
}