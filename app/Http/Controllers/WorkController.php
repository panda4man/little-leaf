<?php

namespace App\Http\Controllers;


use App\Http\Request\Queries\WorkSearchRequest;
use App\Searches\WorkSearch;
use App\Transformers\WorkTransformer;
use Carbon\Carbon;

class WorkController extends Controller
{
    /**
     * Route all requests to the default month view for today's month
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function index()
    {
        $today = Carbon::now()->toDateString();

        return redirect()->route('work-monthly', ['date' => $today]);
    }

    /**
     * @param WorkSearchRequest $req
     * @param WorkSearch $search
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getMonthView(WorkSearchRequest $req, WorkSearch $search)
    {
        $works = $search->params($req)->month()->search();
        $works = fractal()->collection($works, new WorkTransformer())->toArray();

        return view('work.month', [
            'work' => $works
        ]);
    }

    /**
     * @param WorkSearchRequest $req
     * @param WorkSearch $search
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getWeekView(WorkSearchRequest $req, WorkSearch $search)
    {
        $works = $search->params($req)->week()->search();
        $works = fractal()->collection($works, new WorkTransformer())->toArray();

        return view('work.week', [
            'work' => $works
        ]);
    }

    /**
     * @param WorkSearchRequest $req
     * @param WorkSearch $search
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getDayView(WorkSearchRequest $req, WorkSearch $search)
    {
        $works = $search->params($req)->day()->search();
        $works = fractal()->collection($works, new WorkTransformer())->toArray();

        return view('work.day', [
            'work' => $works
        ]);
    }
}
