<?php


namespace App\Repositories;


use App\Models\Deliverable;
use App\Models\Project;

class DeliverableRepository implements iResourceRepository
{
    /**
     * Create a new resource
     *
     * @param array $fields
     * @param array $relations
     * @return mixed
     */
    public function create(array $fields = [], ...$relations)
    {
        $deliverable = null;

        try {
            $deliverable = Deliverable::create($fields);

            foreach($relations as $rel) {
                if($rel instanceof Project) {
                    $deliverable->project()->associate($rel);
                }
            }

            $deliverable->save();
        } catch (\Exception $e) {
            \Log::error($e->getMessage());
        }

        return $deliverable;
    }

    /**
     * Find a resource
     *
     * @param integer $id
     * @return mixed
     */
    public function find(int $id)
    {
        return Deliverable::find($id);
    }

    /**
     * Update a resource
     *
     * @param mixed $identifier
     * @param array $fields
     * @param array $relations
     * @return bool
     */
    public function update($identifier, array $fields = [], ...$relations): bool
    {
        $success = false;
        $deliverable = null;

        if($identifier instanceof Deliverable) {
            $deliverable = $identifier;
        } else {
            $deliverable = Deliverable::find($identifier);
        }

        if(is_null($deliverable))
            return $success;

        $success = $deliverable->update($fields);

        if($success) {
            foreach($relations as $rel) {
                if($rel instanceof Project) {
                    $deliverable->project()->associate($rel);
                }
            }

            $deliverable->save();
        }

        return $success;
    }

    /**
     * Delete a resource
     *
     * @param integer $id
     * @return bool
     */
    public function delete(int $id)
    {
        // TODO: Implement delete() method.
    }
}