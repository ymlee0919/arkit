<?php

namespace ArkitTest\InputValidation;

class DateValidationTest extends ValidationTest
{
    /**
     * @covers \Arkit\Core\Filter\Input\Validator\DateValidator
     */
    public function testDateFormat() : void
    {
        $pivot = date_create_from_format('Y-m-d', '2022-04-22');
        $list = [
            [
                'field' => 'firstDate',
                'format' => 'Y-m-d',
                'date' => $pivot->format('Y-m-d')
            ], [
                'field' => 'secondDate',
                'format' => 'd/m/Y',
                'date' => $pivot->format('d/m/Y')
            ], [
                'field' => 'thirdDate',
                'format' => 'n, j Y',
                'date' => $pivot->format('n, j Y')
            ]
        ];

        foreach ($list as $item)
        {
            $valid = $this->validator->validate($item['field'], $item['date'])->isDate($item['format'])->isValid();
            $this->assertTrue($valid, "The date {$item['date']} have not a valid {$item['format']} format");
        }
    }

    /**
     * @covers \Arkit\Core\Filter\Input\Validator\DateValidator
     */
    public function testInvalidDates() : void
    {
        $pivot = date_create_from_format('Y-m-d', '2022-04-22');
        $list = [
            [
                'field' => 'firstDate',
                'format' => 'Y-m-d',
                'date' => $pivot->format('d / m / Y')
            ],[
                'field' => 'secondDate',
                'format' => 'Y-m-d',
                'date' => '2022-04-32'
            ],[
                'field' => 'thirdDate',
                'format' => 'n, j Y',
                'date' => [23,43,56]
            ]
        ];

        foreach ($list as $item)
        {
            $valid = $this->validator->validate($item['field'], $item['date'])->isDate($item['format'])->isValid();
            $this->assertFalse($valid, "The value " . json_encode($item['date']) ." is valid {$item['format']} format");
        }
    }

    /**
     * @covers \Arkit\Core\Filter\Input\Validator\DateValidator
     */
    public function testBeforeDate() : void
    {
        $pivot = '2022-04-22';
        $date = date_create_from_format('Y-m-d', '2020-01-01');

        $valid = $this->validator->validate('date', $pivot)->isDate('Y-m-d')->isBefore($date)->isValid();
        $this->assertTrue($valid, "The date {$date->format('Y-m-d')} is not before {$pivot}");
    }

    /**
     * @covers \Arkit\Core\Filter\Input\Validator\DateValidator
     */
    public function testInvalidBeforeDate() : void
    {
        $date = '2022-04-22';
        $pivot = [2022, 01, 01];

        $valid = $this->validator->validate('date', $pivot)->isDate('Y-m-d')->isBefore($date)->isValid();
        $this->assertFalse($valid, "The date {$date} is not before pivot given");
    }

    /**
     * @covers \Arkit\Core\Filter\Input\Validator\DateValidator
     */
    public function testAfterDate() : void
    {
        $pivot = '2022-04-22';
        $date = date_create_from_format('Y-m-d', '2023-01-01');

        $valid = $this->validator->validate('date', $pivot)->isDate('Y-m-d')->isAfter($date)->isValid();
        $this->assertTrue($valid, "The date {$date->format('Y-m-d')} is not before {$pivot}");
    }

    /**
     * @covers \Arkit\Core\Filter\Input\Validator\DateValidator
     */
    public function testBetweenDate() : void
    {
        $pivot = '2022-04-22';
        $minDate = '2020-01-01';
        $maxDate = '2023-01-01';

        $valid = $this->validator->validate('date', $pivot)->isDate('Y-m-d')->between($minDate, $maxDate)->isValid();
        $this->assertTrue($valid);
    }

}