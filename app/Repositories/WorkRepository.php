<?php


namespace App\Repositories;


use App\Models\Task;
use App\Models\Work;

class WorkRepository implements iResourceRepository
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
        try {
            $work = Work::create($fields);

            foreach($relations as $rel) {
                if($rel instanceof Task) {
                    $work->task()->associate($rel);
                }
            }

            return $work;
        } catch (\Exception $e) {
            \Log::error($e->getMessage());
            return null;
        }
    }

    /**
     * Find a resource
     *
     * @param integer $id
     * @return mixed
     */
    public function find(int $id)
    {
        return Work::find($id);
    }

    /**
     * Update a resource
     *
     * @param integer $id
     * @param array $fields
     * @return bool
     */
    public function update(int $id, array $fields = [])
    {
        $success = false;
        $work = $this->find($id);

        if(!$work) {
            return $success;
        }

        try {
            $success = $work->update($fields);
        } catch(\Exception $e) {
            \Log::error($e->getMessage());
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
        $work = $this->find($id);

        if($work) {
            return $work->delete();
        }

        return true;
    }
}