<?php

namespace App\Service;

use App\Models\Timesheet;
use App\Models\User;
use Carbon\Carbon;

class TimesheetService
{
    public function getTotalMinutesByType(User $user, string $type = 'work', ?string $from = null, ?string $to = null): int
    {
        $query = Timesheet::where('user_id', $user->id)->where('type', $type);

        if ($from) {
            $query->where('day_in', '>=', Carbon::parse($from));
        }

        if ($to) {
            $query->where('day_out', '<=', Carbon::parse($to));
        }

        return $query->get()->sum(fn($t) => $t->day_in->diffInMinutes($t->day_out));
    }

    public function getFormattedTotalTime(int $totalMinutes): array
    {
        $hours = floor($totalMinutes / 60);
        $minutes = $totalMinutes % 60;

        return [
            'hours' => $hours,
            'minutes' => $minutes,
            'formatted' => "{$hours}h {$minutes}m",
        ];
    }

    public function getPaginatedTimesheets(User $user, string $type = 'work', int $perPage = 10, ?string $from = null, ?string $to = null)
    {
        $query = Timesheet::where('user_id', $user->id)->where('type', $type);

        if ($from) {
            $query->where('day_in', '>=', Carbon::parse($from));
        }

        if ($to) {
            $query->where('day_out', '<=', Carbon::parse($to));
        }

        return $query->orderByDesc('day_in')->paginate($perPage);
    }

    public function transformTimesheets($timesheets)
    {
        return $timesheets->map(function ($item) {
            $start = $item->day_in;
            $end = $item->day_out;
            $minutes = $start->diffInMinutes($end);
            $hours = floor($minutes / 60);
            $minutes = $minutes % 60;

            return [
                'id' => $item->id,
                'start' => $start->toDateTimeSatring(),
                'end' => $end->toDateTimeSatring(),
                'duration' => "{$hours}h {$minutes}m",
                'type' => $item->type,
            ];
        });
    }
}
