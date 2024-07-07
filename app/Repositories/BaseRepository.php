<?php

namespace App\Repositories;

abstract class BaseRepository implements BaseRepositoryInterface
{
    /**
     * @var \Illuminate\Database\Eloquent\Model
     */
    protected $_model;
    public function __construct()
    {
        $this->setModel();
    }

    /**
     * get model
     * @return string
     */
    abstract public function getModel();
    public function setModel()
    {
        $this->_model = app()->make(
            $this->getModel()
        );
    }

    public function getAll($columns = ["*"])
    {
        // TODO: Implement getAll() method.
        return $this->_model->all($columns);
    }

    public function getPaginated($limit = null, $columns = ["*"],$deleted = false)
    {
        // TODO: Implement getPaginated() method.
        $query = $this->_model->paginate($limit, $columns);
        if ($deleted){
            $this->_model->paginate($limit, $columns)->whereNull('deleted_at');
        }
        return $query;
    }

    public function find($id, $columns = ["*"],$deleted = false)
    {
        // TODO: Implement find() method.
        $query = $this->_model->find($id, $columns);
        if ($deleted){
            $this->_model->find($id, $columns)->whereNull('deleted_at');
        }
        return $query;

    }

    public function create(array $attributes)
    {
        // TODO: Implement create() method.
        return $this->_model->create($attributes);
    }

    public function insert(array $attributes)
    {
        // TODO: Implement insert() method.
        return $this->_model->insert($attributes);
    }

    public function update($id, array $attributes)
    {
        // TODO: Implement update() method.
        $model = $this->find($id);
        if ($model) {
            $model->update($attributes);
            return $model;
        }
        return false;
    }

    public function delete($id)
    {
        // TODO: Implement delete() method.
        $model = $this->find($id);
        if ($model) {
            $this->_model->update($id, ['deleted_at' => now()]);
            return $model;
        }
        return false;
    }

    public function insertOrUpdate(array $condition, array $attributes)
    {
        // TODO: Implement insertOrUpdate() method.
        return $this->_model->insertOrUpdate($condition, $attributes);
    }

    public function getBy($condition, $columns = ["*"], $deleted = false)
    {
        $query = $this->_model->where($condition);
        if ($deleted){
            $query->whereNull('deleted_at');
        }
        return $query->get($columns);
    }
    public function findBy($condition, $columns = ["*"], $deleted = false)
    {
        $query = $this->_model->where($condition);
        if ($deleted){
            $query->whereNull('deleted_at');
        }
        return $query->first($columns);
    }
}
