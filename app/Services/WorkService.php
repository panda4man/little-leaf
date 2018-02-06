<?php


namespace App\Services;


use App\Models\Project;
use App\Models\Work;
use Carbon\Carbon;

class WorkService
{
    /**
     * @param $year
     * @param $month
     * @return mixed
     */
    public function forMonth($year, $month)
    {
        $works = Work::whereYear('created_at', $year)
            ->whereMonth('created_at', $month)
            ->get();

        return $works;
    }

    /**
     * @param $year
     * @param $month
     * @param $start
     * @return mixed
     */
    public function forWeek($year, $month, $start)
    {
        $template = "$year-$month-$start";
        $start = Carbon::createFromFormat('Y-m-d', $template)->startOfWeek();
        $end = (clone $start)->endOfWeek();

        $works = Work::whereDate('created_at', '>=', $start)
            ->whereDate('created_at', '<=', $end)
            ->get();

        return $works;
    }

    /**
     * @param $year
     * @param $month
     * @param $day
     * @return mixed
     */
    public function forDay($year, $month, $day)
    {
        $template = "$year-$month-$day";
        $start = Carbon::createFromFormat('Y-m-d', $template)->startOfDay();
        $end = (clone $start)->endOfDay();

        $works = Work::whereDate('created_at', '>=', $start)
            ->whereDate('created_at', '<=', $end)
            ->get();

        return $works;
    }
}