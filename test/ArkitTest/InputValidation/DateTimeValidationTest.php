<?php

namespace ArkitTest\InputValidation;


class DateTimeValidationTest extends ValidationTest
{
    /**
     * @covers \Arkit\Core\Filter\Input\Validator\DateValidator
     */
    public function testDateFormat() : void
    {
        $pivot = date_create_from_format('Y-m-d H:i:s', '2022-04-22 09:20:12');
        $list = [
            [
                'field' => 'firstDate',
                'format' => 'Y-m-d H:i:s',
                'date' => $pivot->format('Y-m-d H:i:s')
            ], [
                'field' => 'secondDate',
                'format' => 'd/m/Y H.i.s',
                'date' => $pivot->format('d/m/Y H.i.s')
            ], [
                'field' => 'thirdDate',
                'format' => 'n, j Y :: H.i.s',
                'date' => $pivot->format('n, j Y :: H.i.s')
            ]
        ];

        foreach ($list as $item)
        {
            $valid = $this->validator->validate($item['field'], $item['date'])->isDateTime($item['format'])->isValid();
            $this->assertTrue($valid, "The date {$item['date']} have not a valid {$item['format']} format");
        }
    }

    /**
     * @covers \Arkit\Core\Filter\Input\Validator\DateValidator
     */
    public function testInvalidDates() : void
    {
        $pivot = date_create_from_format('Y-m-d H:i:s', '2022-04-22 09:20:12');
        $list = [
            [
                'field' => 'firstDate',
                'format' => 'Y-m-d H:i:s',
                'date' => $pivot->format('d / m / Y H.i.s')
            ],[
                'field' => 'secondDate',
                'format' => 'Y-m-d H.i.s',
                'date' => '2022-04-32 13::09::11'
            ],[
                'field' => 'thirdDate',
                'format' => 'n, j Y H-i-s',
                'date' => [23,43,56]
            ]
        ];

        foreach ($list as $item)
        {
            $valid = $this->validator->validate($item['field'], $item['date'])->isDateTime($item['format'])->isValid();
            $this->assertFalse($valid, "The value " . json_encode($item['date']) ." is valid {$item['format']} format");
        }
    }

    /**
     * @covers \Arkit\Core\Filter\Input\Validator\DateValidator
     */
    public function testBeforeDate() : void
    {
        $pivot = '2022-04-22 09.20.12';
        $date = date_create_from_format('Y-m-d H:i:s', '2020-01-01 12:30:00');

        $valid = $this->validator->validate('date', $pivot)->isDateTime('Y-m-d H.i.s')->isBefore($date)->isValid();
        $this->assertTrue($valid, "The date/time {$date->format('Y-m-d H:i:s')} is not before {$pivot}");
    }

    /**
     * @covers \Arkit\Core\Filter\Input\Validator\DateValidator
     */
    public function testInvalidBeforeDate() : void
    {
        $date = '2022-04-22 09:20:12';
        $pivot = [2022, 01, 01];

        $valid = $this->validator->validate('date', $pivot)->isDateTime('Y-m-d H:i:s')->isBefore($date)->isValid();
        $this->assertFalse($valid, "The date {$date} is not before pivot given");
    }

    /**
     * @covers \Arkit\Core\Filter\Input\Validator\DateValidator
     */
    public function testAfterDate() : void
    {
        $pivot = '2022-04-22 09:20:12';
        $date = '2023-01-01 01:00:00';

        $valid = $this->validator->validate('date', $pivot)->isDateTime('Y-m-d H:i:s')->isAfter($date)->isValid();
        $this->assertTrue($valid, "The date {$date} is not before {$pivot}");
    }

    /**
     * @covers \Arkit\Core\Filter\Input\Validator\DateValidator
     */
    public function testBetweenDate() : void
    {
        $pivot = '2022-04-22 12:00:00';
        $minDate = '2020-01-01 12:00:00';
        $maxDate = '2023-01-01 12:00:00';

        $valid = $this->validator->validate('date', $pivot)->isDateTime('Y-m-d H:i:s')->between($minDate, $maxDate)->isValid();
        $this->assertTrue($valid);
    }

}