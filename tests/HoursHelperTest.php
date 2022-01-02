<?php

namespace Label84\HoursHelper\Tests;

use Carbon\Carbon;
use Facades\Label84\HoursHelper\HoursHelper;
use Illuminate\Support\Collection;

class HoursHelperTest extends TestCase
{
    /** @test */
    public function it_can_have_the_start_and_end_value_in_string_format()
    {
        $result = HoursHelper::create('08:00', '09:00', 15);

        $collection = new Collection([
            0 => '08:00',
            1 => '08:15',
            2 => '08:30',
            3 => '08:45',
            4 => '09:00',
        ]);

        $this->assertEquals($collection, $result);
    }

    /** @test */
    public function it_can_have_the_start_and_end_value_in_datetime_format()
    {
        $start = Carbon::createFromFormat('H:i', '08:00');
        $end = Carbon::createFromFormat('H:i', '09:00');

        $result = HoursHelper::create($start, $end, 15);

        $collection = new Collection([
            0 => '08:00',
            1 => '08:15',
            2 => '08:30',
            3 => '08:45',
            4 => '09:00',
        ]);

        $this->assertEquals($collection, $result);
    }

    /** @test */
    public function it_can_have_an_end_time_later_than_midnight()
    {
        $result = HoursHelper::create('23:45', '00:15', 15);

        $collection = new Collection([
            0 => '23:45',
            1 => '00:00',
            2 => '00:15',
        ]);

        $this->assertEquals($collection, $result);
    }

    /** @test */
    public function it_uses_the_h_i_formatter_by_default()
    {
        $result = HoursHelper::create('08:00', '08:30', 30, 'H:i');

        $collection = new Collection([
            0 => '08:00',
            1 => '08:30',
        ]);

        $this->assertEquals($collection, $result);
    }

    /** @test */
    public function it_can_have_dates_in_the_formatter()
    {
        $result = HoursHelper::create('2022-01-01 08:00', '2022-01-01 08:30', 15, 'Y-m-d H:i');

        $collection = new Collection([
            0 => '2022-01-01 08:00',
            1 => '2022-01-01 08:15',
            2 => '2022-01-01 08:30',
        ]);

        $this->assertEquals($collection, $result);
    }

    /** @test */
    public function it_can_have_the_12_hour_format_in_the_formatter()
    {
        $result = HoursHelper::create('11:00', '13:00', 60, 'g:i A');

        $collection = new Collection([
            0 => '11:00 AM',
            1 => '12:00 PM',
            2 => '1:00 PM',
        ]);

        $this->assertEquals($collection, $result);
    }

    /** @test */
    public function it_can_have_an_interval_of_multiple_days()
    {
        $result = HoursHelper::create('2022-01-01', '2022-01-04', 60 * 24 * 1.5, 'Y-m-d H:i');

        $collection = new Collection([
            0 => '2022-01-01 00:00',
            1 => '2022-01-02 12:00',
            2 => '2022-01-04 00:00',
        ]);

        $this->assertEquals($collection, $result);
    }

    /** @test */
    public function it_can_exclude_times()
    {
        $result = HoursHelper::create('08:30', '10:00', 15, 'H:i', [
            ['09:00', '09:30'],
        ]);

        $collection = new Collection([
            0 => '08:30',
            1 => '08:45',
            2 => '09:45',
            3 => '10:00',
        ]);

        $this->assertEquals($collection, $result);
    }

    /** @test */
    public function it_can_exclude_dates()
    {
        $result = HoursHelper::create('2021-01-01 23:30', '2021-01-03 00:30', 15, 'Y-m-d H:i', [
            ['2021-01-02 00:00', '2021-01-02 23:59'],
        ]);

        $collection = new Collection([
            0 => '2021-01-01 23:30',
            1 => '2021-01-01 23:45',
            2 => '2021-01-03 00:00',
            3 => '2021-01-03 00:15',
            4 => '2021-01-03 00:30',
        ]);

        $this->assertEquals($collection, $result);
    }

    /** @test */
    public function it_can_exclude_multiple_time_intervals()
    {
        $result = HoursHelper::create('08:00', '13:00', 60, 'H:i', [
            ['09:00', '09:59'],
            ['11:00', '11:59'],
        ]);

        $collection = new Collection([
            0 => '08:00',
            1 => '10:00',
            2 => '12:00',
            3 => '13:00',
        ]);

        $this->assertEquals($collection, $result);
    }
}
