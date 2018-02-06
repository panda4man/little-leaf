<?php


namespace App\Searches;


use App\Http\Request\Queries\WorkSearchRequest;
use App\Models\Work;
use Carbon\Carbon;

class WorkSearch
{
    /**
     * @var \Illuminate\Database\Eloquent\Builder
     */
    private $query;

    /**
     * @var
     */
    private $params = [];

    /**
     * @var
     */
    private $scope = 'month';

    /**
     * WorkSearch constructor.
     */
    public function __construct()
    {
        $this->query = Work::query();
    }

    /**
     * @param WorkSearchRequest $req
     * @return WorkSearch
     */
    public function params(WorkSearchRequest $req)
    {
        foreach($req->allowedParams as $key) {
            $v = $req->get($key);

            if(method_exists($req, $key . "Transform")) {
                $v = call_user_func_array([$req, $key . "Transform"], [$v]);
            }

            $this->params[$key] = $v;
        }

        return $this;
    }

    /**
     * @return mixed
     */
    public function month()
    {
        $this->scope = 'month';

        return $this;
    }

    /**
     * @return mixed
     */
    public function week()
    {
        $this->scope = 'week';

        return $this;
    }

    /**
     * @return $this
     */
    public function day()
    {
        $this->scope = 'day';

        return $this;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public function search()
    {
        $this->buildQuery();

        return $this->query->get();
    }

    /**
     * @return $this;
     */
    private function buildQuery()
    {
        //Handle filters
        foreach($this->params as $k => $v) {
            if($v) {
                $method = "filterBy" . ucfirst($k);

                if(method_exists($this, $method)) {
                    $this->{$method}($v);
                }
            }
        }

        return $this;
    }

    /**
     * @param int $projectId
     * @return $this
     */
    private function filterByProject($projectId = 0)
    {
        $this->query->whereHas('project', function ($q) use($projectId) {
            $q->where('id', $projectId);
        });

        return $this;
    }

    /**
     * @param int $clientId
     * @return $this
     */
    private function filterByClient($clientId = 0)
    {
        $this->query->whereHas('project.client', function ($q) use($clientId) {
            $q->where('id', $clientId);
        });

        return $this;
    }

    /**
     * @param Carbon $date
     * @return $this
     */
    private function filterByDate(Carbon $date)
    {
        $method = "scopeBy" . ucFirst($this->scope);

        return $this->{$method}($date);
    }

    /**
     * @param Carbon $date
     * @return $this
     */
    private function scopeByMonth(Carbon $date)
    {
        $this->query->whereYear('created_at', $date->year)
            ->whereMonth('created_at', $date->month);

        return $this;
    }

    /**
     * @param Carbon $date
     * @return $this
     */
    private function scopeByWeek(Carbon $date)
    {
        $start = (clone $date)->startOfWeek();
        $end = (clone $start)->endOfWeek();

        $this->query->whereDate('created_at', '>=', $start)
            ->whereDate('created_at', '<=', $end);

        return $this;
    }

    /**
     * @param Carbon $date
     * @return $this
     */
    private function scopebyDay(Carbon $date)
    {
        $start = (clone $date)->startOfDay();
        $end = (clone $start)->endOfDay();

        $this->query->whereDate('created_at', '>=', $start)
            ->whereDate('created_at', '<=', $end);

        return $this;
    }
}