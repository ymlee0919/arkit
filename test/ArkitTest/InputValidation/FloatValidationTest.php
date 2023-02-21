<?php
namespace ArkitTest\InputValidation;

use PHPUnit\Framework\TestCase;

class FloatValidationTest extends ValidationTest
{
    /**
     * @covers \Arkit\Core\Filter\Input\Validator\IntValidator
     */
    public function testValidInt() : void
    {
        $list = [10.98, '35.45', -34.0934, '-54.00', -30.00, 045e-20];

        foreach ($list as $number)
        {
            $valid = $this->validator->validate('number', $number)->isNumeric()->isValid();
            $this->assertTrue($valid, 'The number ' . $number . ' is not numeric');
        }
    }

    /**
     * @covers \Arkit\Core\Filter\Input\Validator\IntValidator
     */
    public function testInvalidInt() : void
    {
        $list = ['35.23s', 'Sample text', 'a19', '045k-20'];

        foreach ($list as $number)
        {
            $valid = $this->validator->validate('number', $number)->isNumeric()->isValid();
            $this->assertFalse($valid, 'The number ' . $number . ' a is numeric');
        }
    }

    /**
     * @covers \Arkit\Core\Filter\Input\Validator\IntValidator
     */
    public function testPositive() : void
    {
        $list = [100.34, 239.11, 48];

        foreach ($list as $number)
        {
            $valid = $this->validator->validate('number', $number)->isNumeric()->isPositive()->isValid();
            $this->assertTrue($valid, 'The number ' . $number . ' is not positive');
        }
    }

    /**
     * @covers \Arkit\Core\Filter\Input\Validator\IntValidator
     */
    public function testNegative() : void
    {
        $list = [-100.23, -239.4, -48];

        foreach ($list as $number)
        {
            $valid = $this->validator->validate('number', $number)->isNumeric()->isNegative()->isValid();
            $this->assertTrue($valid, 'The number ' . $number . ' is not positive');
        }
    }

    /**
     * @covers \Arkit\Core\Filter\Input\Validator\IntValidator
     */
    public function testGreater() : void
    {
        $number = 50.293;
        $pivot = 25.89;
        $valid = $this->validator->validate('number', $number)->isNumeric()->greaterThan($pivot)->isValid();
        $this->assertTrue($valid);
    }

    /**
     * @covers \Arkit\Core\Filter\Input\Validator\IntValidator
     */
    public function testLess() : void
    {
        $number = 10.90;
        $pivot = 25.33;
        $valid = $this->validator->validate('number', $number)->isNumeric()->lessThan($pivot)->isValid();
        $this->assertTrue($valid);
    }

    /**
     * @covers \Arkit\Core\Filter\Input\Validator\IntValidator
     */
    public function testBetween() : void
    {
        $number = 25.88;
        $min = 10.5;
        $max = 30.44;
        $valid = $this->validator->validate('number', $number)->isNumeric()->between($min, $max)->isValid();
        $this->assertTrue($valid);
    }

    /**
     * @covers \Arkit\Core\Filter\Input\Validator\IntValidator
     */
    public function testNotBetween() : void
    {
        $number = 55.44;
        $min = 10.9;
        $max = 30.4;
        $valid = $this->validator->validate('number', $number)->isNumeric()->notBetween($min, $max)->isValid();
        $this->assertTrue($valid);
    }

    /**
     * @covers \Arkit\Core\Filter\Input\Validator\IntValidator
     */
    public function testZero() : void
    {
        $valid = $this->validator->validate('number', 0)->isNumeric()->isValid();
        $this->assertTrue($valid, 'Zero is not valid');

        $valid = $this->validator->validate('number', 0)->isNumeric()->isPositive(true)->isValid();
        $this->assertTrue($valid, 'Zero is not positive');

        $valid = $this->validator->validate('number', 0)->isNumeric()->isNegative(true)->isValid();
        $this->assertTrue($valid, 'Zero is not negative');
    }

    /**
     * @covers \Arkit\Core\Filter\Input\Validator\IntValidator
     */
    public function testNotIncluded() : void
    {
        $min = -20.5;
        $max = 20.5;

        $valid = $this->validator->validate('number', 20.5)->isNumeric()->greaterThan($max, false)->isValid();
        $this->assertFalse($valid, 'Number not bigger');

        $valid = $this->validator->validate('number', -20.5)->isNumeric()->lessThan($min, false)->isValid();
        $this->assertFalse($valid, 'Number not smaller');

        $valid = $this->validator->validate('number', 20.5)->isNumeric()->between($min, $max, false)->isValid();
        $this->assertFalse($valid, 'Number inside range');

        $valid = $this->validator->validate('number', 20.5)->isNumeric()->notBetween($min, $max, false)->isValid();
        $this->assertFalse($valid, 'Number out of range');
    }

}