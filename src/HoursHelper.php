<?php

namespace Label84\HoursHelper;

use Carbon\Carbon;
use Carbon\CarbonInterval;
use Illuminate\Support\Collection;

class HoursHelper
{
    /**
     * Create a Collection of times with a given interval for a given period.
     *
     * @param Carbon|string $start
     * @param Carbon|string $end
     * @param int           $interval
     * @param string        $format
     * @param array         $excludes
     *
     * @return Collection
     */
    public function create($start, $end, int $interval, string $format = 'H:i', array $excludes = []): Collection
    {
        $start = Carbon::parse($start);
        $end = Carbon::parse($end);

        // +1 day if the end time is before the start time AND both are without date/on the same date
        if ($start->isSameDay($end) && $end->isBefore($start)) {
            $end = $end->addDay();
        }

        $period = CarbonInterval::minutes($interval)->toPeriod($start, $end);

        /** @phpstan-ignore-next-line */
        return collect($period)
            ->reject(function (Carbon $carbon) use ($excludes) {
                foreach ($excludes as $exclude) {
                    if ($carbon->between(Carbon::parse($exclude[0]), Carbon::parse($exclude[1]))) {
                        return true;
                    }
                }

                return false;
            })
            ->map->format($format)
            ->values();
    }
}
