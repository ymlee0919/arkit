<?php

namespace ArkitTest\InputValidation;

use PHPUnit\Framework\TestCase;
class StrNumberValidationTest extends ValidationTest
{
    public function validRomanNumberProvider() : array
    {
        return [
            ['I'], ['VI'], ['IV'], ['X'], ['MC'], ['LX']
        ];
    }

    public function invalidRomanNumberProvider() : array
    {
        return [
            ['a'], [1],[[3,4,3,5]], [false], [true], [0]
        ];
    }

    /**
     * @covers \Arkit\Core\Filter\Validator\StrNumberValidator
     * @dataProvider validRomanNumberProvider
     */
    public function testRomanNumber($number) : void
    {
        $valid = $this->validator->validate('roman', $number)->isStrNumber()->isRoman()->isValid();
        $this->assertTrue($valid, "Number {$number} is not valid roman number");
    }

    /**
     * @covers \Arkit\Core\Filter\Validator\StrNumberValidator
     * @dataProvider invalidRomanNumberProvider
     */
    public function testInvalidRomanNumber($number) : void
    {
        $valid = $this->validator->validate('roman', $number)->isStrNumber()->isRoman()->isValid();
        $this->assertFalse($valid, "Number " . json_encode($number) . " is a valid roman number");
    }

    public function validHexadecimalNumberProvider() : array
    {
        return [
            ['0'], ['1'], ['0xF'], ['91aeb89'], ['A'], ['0x900EBF2']
        ];
    }

    public function invalidHexadecimalNumberProvider() : array
    {
        return [
            ['0xHK3490'], [99],[[3,4,3,5]], [false], [true],['0xl230*5']
        ];
    }

    /**
     * @covers \Arkit\Core\Filter\Validator\StrNumberValidator
     * @dataProvider validHexadecimalNumberProvider
     */
    public function testHexadecimalNumber($number) : void
    {
        $valid = $this->validator->validate('hexadecimal', $number)->isStrNumber()->isHexadecimal()->isValid();
        $this->assertTrue($valid, "Number {$number} is not valid hexadecimal number");
    }

    /**
     * @covers \Arkit\Core\Filter\Validator\StrNumberValidator
     * @dataProvider invalidHexadecimalNumberProvider
     */
    public function testInvalidHexhdecimalNumber($number) : void
    {
        $valid = $this->validator->validate('hexadecimal', $number)->isStrNumber()->isHexadecimal()->isValid();
        $this->assertFalse($valid, "Number " . json_encode($number) . " is a valid hexadecimal number");
    }

    public function validOctalNumberProvider() : array
    {
        return [
            ['0'], ['1'], [ '0x8054'], ['2208341 '], ['0x1653237']
        ];
    }

    public function invalidOctalNumberProvider() : array
    {
        return [
            ['0x490'], [99],[[3,4,3,5]], [false], [true],['0xl230*5']
        ];
    }

    /**
     * @covers \Arkit\Core\Filter\Validator\StrNumberValidator
     * @dataProvider validOctalNumberProvider
     */
    public function testOctalNumber($number) : void
    {
        $valid = $this->validator->validate('octal', $number)->isStrNumber()->isOctal()->isValid();
        $this->assertTrue($valid, "Number {$number} is not valid octal number");
    }

    /**
     * @covers \Arkit\Core\Filter\Validator\StrNumberValidator
     * @dataProvider invalidOctalNumberProvider
     */
    public function testInvalidOctalNumber($number) : void
    {
        $valid = $this->validator->validate('octal', $number)->isStrNumber()->isOctal()->isValid();
        $this->assertFalse($valid, "Number " . json_encode($number) . " is a valid octal number");
    }

    public function validBinaryNumberProvider() : array
    {
        return [
            ['0'], ['1 '], [' 0x011011'], ['110011 '], ['1010100011']
        ];
    }

    public function invalidBinaryNumberProvider() : array
    {
        return [
            ['0x490'], [111111],[[3,4,3,5]], [false], [true],['0xl230*5']
        ];
    }

    /**
     * @covers \Arkit\Core\Filter\Validator\StrNumberValidator
     * @dataProvider validBinaryNumberProvider
     */
    public function testBinaryNumber($number) : void
    {
        $valid = $this->validator->validate('binary', $number)->isStrNumber()->isBinary()->isValid();
        $this->assertTrue($valid, "Number {$number} is not valid binary number");
    }

    /**
     * @covers \Arkit\Core\Filter\Validator\StrNumberValidator
     * @dataProvider invalidOctalNumberProvider
     */
    public function testInvalidBinaryNumber($number) : void
    {
        $valid = $this->validator->validate('binary', $number)->isStrNumber()->isBinary()->isValid();
        $this->assertFalse($valid, "Number " . json_encode($number) . " is a valid binary number");
    }

    public function validRgbProvider() : array
    {
        return [
            ['AA564F'], ['ffffff'], ['#000000 '], [' 110011'], ['#00ff00']
        ];
    }

    public function invalidRgbProvider() : array
    {
        return [
            ['0x490'], [111111],[[3,4,3,5]], [false], [true],['0xl230*5'], ['#4565455444'], ['#456']
        ];
    }

    /**
     * @covers \Arkit\Core\Filter\Validator\StrNumberValidator
     * @dataProvider validRgbProvider
     */
    public function testRGB($number) : void
    {
        $valid = $this->validator->validate('color', $number)->isStrNumber()->isRgbColor()->isValid();
        $this->assertTrue($valid, "Number {$number} is not valid RGB color");
    }

    /**
     * @covers \Arkit\Core\Filter\Validator\StrNumberValidator
     * @dataProvider invalidRgbProvider
     */
    public function testInvalidRGB($number) : void
    {
        $valid = $this->validator->validate('color', $number)->isStrNumber()->isBinary()->isValid();
        $this->assertFalse($valid, "Number " . json_encode($number) . " is a valid RGB color");
    }
}