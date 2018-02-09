<?php

namespace App\Transformers;

use League\Fractal\TransformerAbstract;

class ProjectTransformer extends TransformerAbstract
{
    /**
     * @var array
     */
    protected $availableIncludes = ['client', 'deliverables', 'work'];

    /**
     * A Fractal transformer.
     *
     * @param $project
     * @return array
     */
    public function transform($project)
    {
        $data = null;

        if(!$project)
            return $data;

        $data = [
            'id'              => $project->id,
            'name'            => $project->name,
            'estimated_cost'  => $project->estimated_cost,
            'estimated_hours' => $project->estimated_hours,
            'due_at'          => $project->due_at ? $project->due_at->format('Y-m-d') : null,
            'completed_at'    => $project->completed_at ? $project->completed_at->format('Y-m-d') : null,
        ];

        return $data;
    }

    /**
     * @param $project
     * @return \League\Fractal\Resource\Item|\League\Fractal\Resource\NullResource
     */
    public function includeClient($project)
    {
        if(!$project)
            return $this->null();

        $project->load('client');

        return $this->item($project->client, new ClientTransformer());
    }

    /**
     * @param $project
     * @return \League\Fractal\Resource\Collection|\League\Fractal\Resource\NullResource
     */
    public function includeDeliverables($project)
    {
        if(!$project)
            return $this->null();

        $project->load('deliverables');

        return $this->collection($project->deliverables, new DeliverableTransformer());
    }
}
