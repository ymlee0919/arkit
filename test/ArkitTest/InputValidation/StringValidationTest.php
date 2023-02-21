<?php

namespace ArkitTest\InputValidation;

class StringValidationTest extends ValidationTest
{

    public function inputProvider() : array
    {
        return [
            [true], [0], [489], [[5, 6, 4, 'hola']]
        ];
    }

    /**
     * @dataProvider inputProvider
     * @covers \Arkit\Core\Filter\Input\Validator\StringValidator
     */
    public function testInvalidInput($input)
    {
        $valid = $this->validator->validate('input', $input)->isString()->withLengthBetween(25, 45)->isValid();
        $this->assertFalse($valid, json_encode($input) . ' with length between 25 and 45');

        $valid = $this->validator->validate('input', $input)->isString()->wordsCount(20, 50)->isValid();
        $this->assertFalse($valid, json_encode($input) . ' with words between 20 and 50');

        $valid = $this->validator->validate('input', $input)->isString()->contains('word')->isValid();
        $this->assertFalse($valid, json_encode($input) . ' contains "word"');

        $valid = $this->validator->validate('input', $input)->isString()->startWith('word')->isValid();
        $this->assertFalse($valid, json_encode($input) . ' start with "word"');

        $valid = $this->validator->validate('input', $input)->isString()->endWith('word')->isValid();
        $this->assertFalse($valid, json_encode($input) . ' end with "word"');

        $valid = $this->validator->validate('input', $input)->isString()->matchWith('/^[A-Z]+$/')->isValid();
        $this->assertFalse($valid);

        $valid = $this->validator->validate('input', $input)->isString()->isOneOf(['word', '5', '46'])->isValid();
        $this->assertFalse($valid, json_encode($input) . ' inside list');

        $valid = $this->validator->validate('input', $input)->isString()->matchWithAny(['/^[A-Z]+$/', '/^[a-z]+$/'])->isValid();
        $this->assertFalse($valid, json_encode($input) . ' inside patters');
    }

    public function textProvider() : array
    {
        return [
            ['The European languages are members of the same family. Their separate existence is a myth. For science, music, sport, etc, Europe uses the same vocabulary. The languages only differ in their grammar.'],
            ['Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Aenean commodo ligula eget dolor. Aenean massa. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Donec quam felis, ultricies nec, pellentesque eu, pretium quis, sem. Nulla consequat massa quis enim.'],
            ['But I must explain to you how all this mistaken idea of denouncing pleasure and praising pain was born and I will give you a complete account of the system, and expound the actual teachings of the great explorer of the truth, the master-builder of human happiness.'],
            ['A wonderful serenity has taken possession of my entire soul, like these sweet mornings of spring which I enjoy with my whole heart. I am alone, and feel the charm of existence in this spot, which was.']
        ];
    }

    /**
     * @dataProvider textProvider
     * @covers \Arkit\Core\Filter\Input\Validator\StringValidator
     */
    public function testValidTextLength($text) : void
    {
        $length = strlen($text);
        $valid = $this->validator->validate('text', $text)->isString()->withLengthBetween($length - 50)->isValid();
        $this->assertTrue($valid);
        $valid = $this->validator->validate('text', $text)->isString()->withLengthBetween($length - 50, $length + 20)->isValid();
        $this->assertTrue($valid);
    }

    /**
     * @dataProvider textProvider
     * @covers \Arkit\Core\Filter\Input\Validator\StringValidator
     */
    public function testInvalidTextLength($text) : void
    {
        $length = strlen($text);
        $valid = $this->validator->validate('text', $text)->isString()->withLengthBetween($length + 50)->isValid();
        $this->assertFalse($valid);

        $valid = $this->validator->validate('text', $text)->isString()->withLengthBetween($length - 50, $length - 15)->isValid();
        $this->assertFalse($valid);

        $valid = $this->validator->validate('text', [23, 54, 9])->isString()->withLengthBetween($length - 50, $length - 15)->isValid();
        $this->assertFalse($valid);
    }

    public function wordsProvider() : array
    {
        return [
            ['A wonderful serenity has taken possession of my entire soul, like these sweet mornings of spring  which I enjoy with my whole heart. I am alone, and feel the charm of existence in this spot.'],
            ['The European languages are members of the same family. Their separate existence is a myth. For science, music, sport, etc..., Europe uses the same vocabulary? The languages only differ in their grammar, their  pronunciation and   their most common words. [European Text]'],
            ['By faith he made his home in the promised land like a stranger in a foreign country - like France -; he  lived in tents, as did Isaac and Jacob, who were heirs with him of the same promise.'],
            ['But Samuel replied: "Does the LORD delight in burnt offerings and sacrifices as much as in obeying the LORD? To obey is better than sacrifice, and to heed is better than the fat of rams. (1 Samuel 15:22)']
        ];
    }

    /**
     * @dataProvider wordsProvider
     * @covers \Arkit\Core\Filter\Input\Validator\StringValidator
     */
    public function testValidWordCount($text) : void
    {
        $valid = $this->validator->validate('words', $text)->isString()->wordsCount(25, 70)->isValid();
        $this->assertTrue($valid);
    }

    /**
     * @dataProvider wordsProvider
     * @covers \Arkit\Core\Filter\Input\Validator\StringValidator
     */
    public function testInvalidWordCount($text) : void
    {
        $valid = $this->validator->validate('words', $text)->isString()->wordsCount(70)->isValid();
        $this->assertFalse($valid);

        $valid = $this->validator->validate('words', $text)->isString()->wordsCount(90, 150)->isValid();
        $this->assertFalse($valid);
    }


}