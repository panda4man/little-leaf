<?php


namespace App\Repositories;


use App\Models\Task;
use App\Models\User;

class TaskRepository implements iResourceRepository
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
            $task = Task::create($fields);

            foreach($relations as $rel) {
                if($rel instanceof User) {
                    $task->user()->associate($rel);
                }
            }

            return $task;
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
        return Task::find($id);
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
        $task = $this->find($id);

        if(!$task) {
            return $success;
        }

        try {
            $success = $task->update($fields);
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
        $task = $this->find($id);

        if($task) {
            return $task->delete();
        }

        return true;
    }
}