<?php

namespace App\Repositories;

interface iResourceRepository
{
    /**
     * Create a new resource
     *
     * @param array $fields
     * @param array $relations
     * @return mixed
     */
    public function create(array $fields = [], ...$relations);

    /**
     * Find a resource
     *
     * @param integer $id
     * @return mixed
     */
    public function find(int $id);

    /**
     * Update a resource
     *
     * @param integer $id
     * @param array $fields
     * @param array $relations
     * @return bool
     */
    public function update(int $id, array $fields = [], ...$relations);

    /**
     * Delete a resource
     *
     * @param integer $id
     * @return bool
     */
    public function delete(int $id);
}