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
     * @param mixed $identifier
     * @param array $fields
     * @param array $relations
     * @return bool
     */
    public function update($identifier, array $fields = [], ...$relations): bool
    {
        $success = false;
        $task = null;

        if($identifier instanceof Task) {
            $task = $identifier;
        } else {
            $task = Task::find($identifier);
        }

        if(!$task) {
            return $success;
        }

        $success = $task->update($fields);

        if($success) {
            foreach($relations as $rel) {
                if($rel instanceof User) {
                    $task->user()->associate($rel);
                }
            }
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