<?php

namespace App\Repository;

use App\Interface\BaseRepositoryInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class BaseRepository implements BaseRepositoryInterface
{

    protected $model;

    public function __construct(Model $model)
    {
        $this->model = $model;
    }


    public function save(Model $object)
    {
        return  DB::transaction(function () use ($object) {
            $object->save();
            return $object;
        });
    }

    public function saveAll(array $list)
    {
        return DB::transaction(function () use ($list) {
            $list =  $this->model->newQuery()->insert($list);
            return $list;
        });
    }

    public function update(array $attributes, int $id): bool
    {
        return DB::transaction(function () use ($attributes, $id) {
             return $this->find($id)->update($attributes);
        });
    }

    public function all($columns = array('*'), string $orderBy = 'id', string $sortBy = 'asc')
    {
        return $this->model->newQuery()->orderBy($orderBy, $sortBy)->get($columns);
    }

    public function find(int $id)
    {
        return $this->model->newQuery()->where('id', $id)->first();
    }

    public function findOneOrFail(int $id)
    {
        return $this->model->newQuery()->findOrFail($id);
    }

    public function findBy(array $data)
    {
        return $this->model->newQuery()->where($data)->get();
    }

    public function count()
    {
        return $this->model->newQuery()->count();
    }

    public function findAll(array $data, int $pageSize = 10, string $orderBy = 'id', string $sortBy = 'asc')
    {
        return $this->model->newQuery()->where($data)->orderBy($orderBy, $sortBy)->paginate($pageSize);
    }

    public function findOneBy(array $data)
    {
        return $this->model->newQuery()->where($data)->first();
    }

    public function findOneByOrFail(array $data)
    {
        return $this->model->newQuery()->where($data)->firstOrFail();
    }

    public function delete(int $id): bool
    {
        return $this->model->newQuery()->where(['id' => $id])->delete();
    }

    public function deleteAll(array $array, $withDetail = false): bool
    {
        return DB::transaction(function () use ($array) {
            return $this->model->newQuery()->where($array)->delete();
        });
    }
}
