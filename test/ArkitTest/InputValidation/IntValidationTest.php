<?php
namespace ArkitTest\InputValidation;

use PHPUnit\Framework\TestCase;

class IntValidationTest extends ValidationTest
{
    /**
     * @covers \Arkit\Core\Filter\Input\Validator\IntValidator
     */
    public function testValidInt() : void
    {
        $list = [10, '35', -34, '-54'];

        foreach ($list as $number)
        {
            $valid = $this->validator->validate('number', $number)->isInteger()->isValid();
            $this->assertTrue($valid, 'The number ' . $number . ' is not integer');
        }
    }

    /**
     * @covers \Arkit\Core\Filter\Input\Validator\IntValidator
     */
    public function testInvalidInt() : void
    {
        $list = [10.09, '35.23', 'Sample text', 'a19', '045'];

        foreach ($list as $number)
        {
            $valid = $this->validator->validate('number', $number)->isInteger()->isValid();
            $this->assertFalse($valid, 'The number ' . $number . ' is integer');
        }
    }

    /**
     * @covers \Arkit\Core\Filter\Input\Validator\IntValidator
     */
    public function testPositive() : void
    {
        $list = [100, 239, 48];

        foreach ($list as $number)
        {
            $valid = $this->validator->validate('number', $number)->isInteger()->isPositive()->isValid();
            $this->assertTrue($valid, 'The number ' . $number . ' is not positive');
        }
    }

    /**
     * @covers \Arkit\Core\Filter\Input\Validator\IntValidator
     */
    public function testNegative() : void
    {
        $list = [-100, -239, -48];

        foreach ($list as $number)
        {
            $valid = $this->validator->validate('number', $number)->isInteger()->isNegative()->isValid();
            $this->assertTrue($valid, 'The number ' . $number . ' is not positive');
        }
    }

    /**
     * @covers \Arkit\Core\Filter\Input\Validator\IntValidator
     */
    public function testGreater() : void
    {
        $number = 50;
        $pivot = 25;
        $valid = $this->validator->validate('number', $number)->isInteger()->greaterThan($pivot)->isValid();
        $this->assertTrue($valid);
    }

    /**
     * @covers \Arkit\Core\Filter\Input\Validator\IntValidator
     */
    public function testLess() : void
    {
        $number = 10;
        $pivot = 25;
        $valid = $this->validator->validate('number', $number)->isInteger()->lessThan($pivot)->isValid();
        $this->assertTrue($valid);
    }

    /**
     * @covers \Arkit\Core\Filter\Input\Validator\IntValidator
     */
    public function testBetween() : void
    {
        $number = 25;
        $min = 10;
        $max = 30;
        $valid = $this->validator->validate('number', $number)->isInteger()->between($min, $max)->isValid();
        $this->assertTrue($valid);
    }

    /**
     * @covers \Arkit\Core\Filter\Input\Validator\IntValidator
     */
    public function testNotBetween() : void
    {
        $number = 55;
        $min = 10;
        $max = 30;
        $valid = $this->validator->validate('number', $number)->isInteger()->notBetween($min, $max)->isValid();
        $this->assertTrue($valid);
    }

    /**
     * @covers \Arkit\Core\Filter\Input\Validator\IntValidator
     */
    public function testZero() : void
    {
        $valid = $this->validator->validate('number', 0)->isInteger()->isValid();
        $this->assertTrue($valid, 'Zero is not valid');

        $valid = $this->validator->validate('number', 0)->isInteger()->isPositive(true)->isValid();
        $this->assertTrue($valid, 'Zero is not positive');

        $valid = $this->validator->validate('number', 0)->isInteger()->isNegative(true)->isValid();
        $this->assertTrue($valid, 'Zero is not negative');
    }

    /**
     * @covers \Arkit\Core\Filter\Input\Validator\IntValidator
     */
    public function testNotIncluded() : void
    {
        $min = -20;
        $max = 20;

        $valid = $this->validator->validate('number', 20)->isInteger()->greaterThan($max, false)->isValid();
        $this->assertFalse($valid, 'Integer not bigger');

        $valid = $this->validator->validate('number', -20)->isInteger()->lessThan($min, false)->isValid();
        $this->assertFalse($valid, 'Integer not smaller');

        $valid = $this->validator->validate('number', 20)->isInteger()->between($min, $max, false)->isValid();
        $this->assertFalse($valid, 'Integer inside range');

        $valid = $this->validator->validate('number', 20)->isInteger()->notBetween($min, $max, false)->isValid();
        $this->assertFalse($valid, 'Integer out of range');
    }

}