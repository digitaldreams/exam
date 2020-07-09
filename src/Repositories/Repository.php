<?php

namespace App\Repositories;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Repository.
 */
abstract class Repository
{

    /**
     * @var \Illuminate\Database\Eloquent\Model;
     */
    protected $model;

    /**
     * Get list of Resources.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function all(): Collection
    {
        return $this->model->all();
    }

    /**
     *  Returns a paginated list of users.
     *
     * @param int    $perPage
     * @param array  $columns
     * @param string $pageName
     * @param int    $page
     *
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function paginate(
        int $perPage = null,
        array $columns = ['*'],
        string $pageName = 'page',
        int $page = null
    ): LengthAwarePaginator {
        return $this->model->paginate($perPage, $columns, $pageName, $page);
    }

    /**
     * Create a new Resource.
     *
     * @param array $data
     *
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function create(array $data): Model
    {
        return $this->model->create($data);
    }

    /**
     * Update an existing Resource.
     *
     * @param array                               $data
     * @param \Illuminate\Database\Eloquent\Model $model
     *
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function update(array $data, Model $model): Model
    {
        $model->update($data);

        return $model;
    }

    /**
     * Delete an existing Resource.
     *
     * @param \Illuminate\Database\Eloquent\Model $model
     *
     * @return int
     *
     * @throws \Exception
     */
    public function delete(Model $model): int
    {
        return $model->delete();
    }

    /**
     * Get details of a specific Resource.
     *
     * @param $id
     *
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function show($id): Model
    {
        return $this->model->findOrFail($id);
    }

    /**
     * Filter models by their ID.
     *
     * @param array $ids
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function filterById(array $ids): Collection
    {
        return $this->model->newQuery()->whereIn('id', $ids)->get();
    }
}
