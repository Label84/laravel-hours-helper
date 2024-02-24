<?php

namespace Label84\HoursHelper\Tests;

use Carbon\Carbon;
use Illuminate\Support\Collection;
use Label84\HoursHelper\Facades\HoursHelper;

class HoursHelperTest extends TestCase
{
    public function test_it_can_have_the_start_and_end_value_in_string_format()
    {
        $result = HoursHelper::create('08:00', '09:00', 15);

        $collection = new Collection([
            '08:00',
            '08:15',
            '08:30',
            '08:45',
            '09:00',
        ]);

        $this->assertEquals($collection, $result);
    }

    public function test_it_can_have_the_start_and_end_value_in_datetime_format()
    {
        $start = Carbon::createFromFormat('H:i', '08:00');
        $end = Carbon::createFromFormat('H:i', '09:00');

        $result = HoursHelper::create($start, $end, 15);

        $collection = new Collection([
            '08:00',
            '08:15',
            '08:30',
            '08:45',
            '09:00',
        ]);

        $this->assertEquals($collection, $result);
    }

    public function test_it_can_have_an_end_time_later_than_midnight()
    {
        $result = HoursHelper::create('23:45', '00:15', 15);

        $collection = new Collection([
            '23:45',
            '00:00',
            '00:15',
        ]);

        $this->assertEquals($collection, $result);
    }

    public function test_it_uses_the_h_i_formatter_by_default()
    {
        $result = HoursHelper::create('08:00', '08:30', 30, 'H:i');

        $collection = new Collection([
            '08:00',
            '08:30',
        ]);

        $this->assertEquals($collection, $result);
    }

    public function test_it_can_have_dates_in_the_formatter()
    {
        $result = HoursHelper::create('2022-01-01 08:00', '2022-01-01 08:30', 15, 'Y-m-d H:i');

        $collection = new Collection([
            '2022-01-01 08:00',
            '2022-01-01 08:15',
            '2022-01-01 08:30',
        ]);

        $this->assertEquals($collection, $result);
    }

    public function test_it_can_have_the_12_hour_format_in_the_formatter()
    {
        $result = HoursHelper::create('11:00', '13:00', 60, 'g:i A');

        $collection = new Collection([
            '11:00 AM',
            '12:00 PM',
            '1:00 PM',
        ]);

        $this->assertEquals($collection, $result);
    }

    public function test_it_can_have_an_interval_of_multiple_days()
    {
        $result = HoursHelper::create('2022-01-01', '2022-01-04', 60 * 24 * 1.5, 'Y-m-d H:i');

        $collection = new Collection([
            '2022-01-01 00:00',
            '2022-01-02 12:00',
            '2022-01-04 00:00',
        ]);

        $this->assertEquals($collection, $result);
    }

    public function test_it_can_exclude_times()
    {
        $result = HoursHelper::create('08:30', '10:00', 15, 'H:i', [
            ['09:00', '09:30'],
        ]);

        $collection = new Collection([
            '08:30',
            '08:45',
            '09:45',
            '10:00',
        ]);

        $this->assertEquals($collection, $result);
    }

    public function test_it_can_exclude_dates()
    {
        $result = HoursHelper::create('2021-01-01 23:30', '2021-01-03 00:30', 15, 'Y-m-d H:i', [
            ['2021-01-02 00:00', '2021-01-02 23:59'],
        ]);

        $collection = new Collection([
            '2021-01-01 23:30',
            '2021-01-01 23:45',
            '2021-01-03 00:00',
            '2021-01-03 00:15',
            '2021-01-03 00:30',
        ]);

        $this->assertEquals($collection, $result);
    }

    public function test_it_can_exclude_multiple_time_intervals()
    {
        $result = HoursHelper::create('08:00', '13:00', 60, 'H:i', [
            ['09:00', '09:59'],
            ['11:00', '11:59'],
        ]);

        $collection = new Collection([
            '08:00',
            '10:00',
            '12:00',
            '13:00',
        ]);

        $this->assertEquals($collection, $result);
    }

    public function test_it_can_exclude_time_interval_that_is_past_midnight()
    {
        $result = HoursHelper::create('22:00', '03:00', 60, 'H:i', [
            ['01:00', '02:00'],
        ]);

        $collection = new Collection([
            '22:00',
            '23:00',
            '00:00',
            '03:00',
        ]);

        $this->assertEquals($collection, $result);
    }

    public function test_it_can_exclude_time_intervals_that_are_before_midnight_and_past_midnight()
    {
        $result = HoursHelper::create('18:00', '05:00', 60, 'H:i', [
            ['21:00', '23:00'],
            ['01:00', '02:00'],
        ]);

        $collection = new Collection([
            '18:00',
            '19:00',
            '20:00',
            '00:00',
            '03:00',
            '04:00',
            '05:00',
        ]);

        $this->assertEquals($collection, $result);
    }

    public function test_it_can_exclude_time_interval_including_midnight()
    {
        $result = HoursHelper::create('23:00', '00:30', 15, 'H:i', [
            ['23:45', '00:14'],
        ]);

        $collection = new Collection([
            '23:00',
            '23:15',
            '23:30',
            '00:15',
            '00:30',
        ]);

        $this->assertEquals($collection, $result);
    }

    public function test_it_can_exclude_dates_that_are_during_midnight()
    {
        $result = HoursHelper::create('2021-01-01 23:30', '2021-01-03 01:00', 15, 'Y-m-d H:i', [
            ['2021-01-01 23:45', '2021-01-03 00:30'],
        ]);

        $collection = new Collection([
            '2021-01-01 23:30',
            '2021-01-03 00:45',
            '2021-01-03 01:00',
        ]);

        $this->assertEquals($collection, $result);
    }

    public function test_it_can_exclude_dates_that_are_on_midnight()
    {
        $result = HoursHelper::create('2023-12-19 23:30', '2023-12-21 01:00', 15, 'Y-m-d H:i', [
            ['2023-12-20 00:00', '2023-12-21 00:30'],
        ]);

        $collection = new Collection([
            '2023-12-19 23:30',
            '2023-12-19 23:45',
            '2023-12-21 00:45',
            '2023-12-21 01:00',
        ]);

        $this->assertEquals($collection, $result);
    }
}
