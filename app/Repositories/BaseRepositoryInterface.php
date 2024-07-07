<?php

namespace App\Repositories;

interface BaseRepositoryInterface
{
    /**
     * Get all
     * @return mixed
     */
    public function getAll();

    /**
     * Get paginated
     * @return mixed
     */
    public function getPaginated();

    /**
     * Get one
     * @param $id
     * @return mixed
     */
    public function find($id);

    /**
     * Create
     * @param array $attributes
     * @return mixed
     */
    public function create(array $attributes);


    /**
     * Insert
     * @param array $attributes
     * @return mixed
     */
    public function insert(array $attributes);

    /**
     * Update
     * @param $id
     * @param array $attributes
     * @return mixed
     */
    public function update($id, array $attributes);

    /**
     * Delete
     * @param $id
     * @return mixed
     */
    public function delete($id);

    /**
     * Insert or update
     * @param array $condition
     * @param array $attributes
     * @return mixed
     */
    public function insertOrUpdate(array $condition, array $attributes);
    public function getBy($condition);
    public function findBy($condition)  ;
}
