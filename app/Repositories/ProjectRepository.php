<?php


namespace App\Repositories;


use App\Models\Client;
use App\Models\Project;

class ProjectRepository implements iResourceRepository
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
            $project = Project::create($fields);

            foreach($relations as $rel) {
                if($rel instanceof Client) {
                    $project->client()->associate($rel);
                }
            }

            $project->save();

            return $project;
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
        return Project::find($id);
    }

    /**
     * Update a resource
     *
     * @param $identifier
     * @param array $fields
     * @param array $relations
     * @return bool
     */
    public function update($identifier, array $fields = [], ...$relations): bool
    {
        $success = false;
        $project = null;

        if($identifier instanceof Project) {
            $project = $identifier;
        } else {
            $project = Project::find($identifier);
        }

        if(!$project) {
            return $success;
        }

        $success = $project->update($fields);

        if($success) {
            foreach($relations as $rel) {
                if($rel instanceof Client) {
                    $project->client()->associate($rel);
                }
            }

            $project->save();
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
        $project = $this->find($id);

        if($project) {
            return $project->delete();
        }

        return true;
    }
}