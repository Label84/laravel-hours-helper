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
     * @param  Carbon|string  $start
     * @param  Carbon|string  $end
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
                    $start = Carbon::parse($exclude[0]);
                    $end = Carbon::parse($exclude[1]);

                    if ($start->gt($end)) {
                        $end->addDay();
                    }

                    if ($start->isToday() && $end->isToday() && $carbon->isTomorrow()) {
                        $start->addDay();
                        $end->addDay();
                    }

                    if ($carbon->between($start, $end)) {
                        return true;
                    }
                }

                return false;
            })
            ->map->format($format)
            ->values();
    }
}
