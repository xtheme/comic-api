<?php

namespace App\Repositories;

use App\Repositories\Contracts\RepositoryInterface;
use Illuminate\Database\Eloquent\Model;

abstract class Repository implements RepositoryInterface
{
    /**
     * @var Model
     */
    protected $model;

    /**
     * @return string
     */
    abstract public function model(): string;

    /**
     * Instantiate a new repository instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->model = app($this->model());
    }

    /**
     * @param  $id
     *
     * @return Model
     */
    public function find($id): ?Model
    {
        return $this->model->findOrFail($id);
    }

    /**
     * @param array $input
     *
     * @return Model
     */
    public function create(array $input = []): ?Model
    {
        return $this->model::create($input);
    }

    /**
     * @param $id
     * @param array $input
     *
     * @return bool
     */
    public function update($id, array $input = []): bool
    {
        $model = $this->find($id);
        $model->fill($input);
        return $model->save();
    }

    /**
     * @param $id
     * @param $field
     * @param $value
     */
    public function editable($id, $field, $value)
    {
        $this->model::where('id', $id)->update([$field => $value]);
    }

    /**
     * @param $id
     */
    public function destroy($id): bool
    {
        return $this->model::findOrFail($id)->delete();
    }
}
