<?php

namespace App\Transformers;

use League\Fractal\TransformerAbstract;

class DeliverableTransformer extends TransformerAbstract
{
    /**
     * @var array
     */
    protected $availableIncludes = ['client', 'work'];

    /**
     * A Fractal transformer.
     *
     * @param $deliverable
     * @return array
     */
    public function transform($deliverable)
    {
        $data = null;

        if(!$deliverable) {
            return $data;
        }

        $data = [
            'id'              => $deliverable->id,
            'project_id'      => $deliverable->project_id,
            'name'            => $deliverable->name,
            'description'     => $deliverable->description,
            'estimated_cost'  => $deliverable->estimated_cost,
            'estimated_hours' => $deliverable->estimated_hours,
            'due_at'          => $deliverable->due_at ? $deliverable->due_at->format('Y-m-d') : null,
            'completed_at'    => $deliverable->completed_at ? $deliverable->completed_at->format('Y-m-d') : null,
        ];

        return $data;
    }

    /**
     * @param $deliverable
     * @return \League\Fractal\Resource\Item|\League\Fractal\Resource\NullResource
     */
    public function includeClient($deliverable)
    {
        if(!$deliverable) {
            return $this->null();
        }

        return $this->item($deliverable->client, new ClientTransformer());
    }

    /**
     * @param $deliverable
     * @return \League\Fractal\Resource\Collection|\League\Fractal\Resource\NullResource
     */
    public function includeWork($deliverable)
    {
        if(!$deliverable) {
            return $this->null();
        }

        return $this->collection($deliverable->work, new WorkTransformer());
    }
}
