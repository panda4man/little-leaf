<?php

namespace App\Transformers;

use League\Fractal\TransformerAbstract;

class WorkTransformer extends TransformerAbstract
{
    /**
     * @var array
     */
    protected $availableIncludes = ['task'];

    /**
     * @var array
     */
    protected $defaultIncludes = ['task'];

    /**
     * A Fractal transformer.
     *
     * @param $work
     * @return array
     */
    public function transform($work)
    {
        $data = null;

        if(!$work)
            return $data;

        $data = [
            'id'          => $work->id,
            'description' => $work->description,
            'hours'       => $work->hours,
            'day'         => $work->created_at->format('Y-m-d'),
        ];

        return $data;
    }

    /**
     * @param $work
     * @return \League\Fractal\Resource\Item|\League\Fractal\Resource\NullResource
     */
    public function includeTask($work)
    {
        if(!$work)
            return $this->null();

        $work->load('task');

        return $this->item($work->task, new TaskTransformer());
    }
}
