<?php

namespace App\Http\Controllers;


use App\Services\WorkService;
use App\Transformers\WorkTransformer;
use Carbon\Carbon;

class WorkController extends Controller
{
    /**
     * @param WorkService $service
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getMonthView(WorkService $service)
    {
        $date = request()->get('date');
        $date = Carbon::createFromFormat('Y-m-d', $date);
        $works = $service->forMonth($date->year, $date->month);
        $works = fractal()->collection($works, new WorkTransformer())->toArray();

        return view('work.month', [
            'work' => $works
        ]);
    }

    public function getWeekView(WorkService $service)
    {
        return view('work.week');
    }

    public function getDayView(WorkService $service)
    {
        return view('work.day');
    }
}
