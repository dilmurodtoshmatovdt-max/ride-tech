<?php

namespace App\Repositories;

use App\Exceptions\UnknownException;
use Carbon\Carbon;
use Closure;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Request;

class BaseRepository
{
    public function __construct(public Model $model)
    {
    }

    public function setTable($tableName)
    {
        return $this->model->setTable($tableName);
    }

    public function selectAll()
    {
        return $this->model->get();
    }

    public function selectAllWithPagination(array | string $columns = ['*'], string $pageName = "page", int | null $page = null, Closure | int | null $perPage = 15)
    {
        $perPage = (int) Request::get('perPage', $perPage);
        if($perPage == (-1)){
            return $this->model->withQueryFilters()->get();
        }
        return $this->model->withQueryFilters()->paginate($perPage, $columns, $pageName, $page);
    }

    public function selectById($id)
    {
        $model = $this->model->where('id', $id)->first();
        if(!$model){
            throw new UnknownException(__('Unknown error: Data not found!'), entityName: $this->model->getTable());
        }
        return $model;
    }

    public function selectByIdWithLogs($id)
    {
        $model = $this->model->where('id', $id)->with('logs')->first();
        if(!$model){
            throw new UnknownException(__('Unknown error: Data not found!'));
        }
        return $model;
    }
    public function selectByUuid($uuid)
    {
        $model = $this->model->where('uuid', $uuid)->first();
        if(!$model){
            throw new UnknownException(__('Unknown error: Data not found!'));
        }
        return $model;
    }

    public function selectByIdWithoutFail($id)
    {
        return $this->model->where('id', $id)->first();
    }

    public function insert(array $data, $primaryKeyOne = 'id', $primaryKeyTwo = null)
    {
        $insertData = [];
        foreach($data as $key=>$value){
            if(!is_null($data[$key])){
                $insertData[$key] = $value;
            }
        }
        $model = $this->model->create($insertData);
        if($primaryKeyOne == 'id'){
            $data['id'] = $model['id'];
        }
        return $this->model
            ->when($primaryKeyOne, fn($query) => $query->where($primaryKeyOne, $data[$primaryKeyOne]))
            ->when($primaryKeyTwo, fn($query) => $query->where($primaryKeyTwo, $data[$primaryKeyTwo]))
            ->first();
    }

    public function insertOrUpdate(array $data, array $condition)
    {
        return $this->model->updateOrCreate($condition, $data);
    }

    public function insertWithTableNamePostfix(array $data, $tableNamePostfix)
    {
        return $this->model->setTable($this->model->getTable() . '_' . $tableNamePostfix)->create($data);
    }

    public function insertMany(array $data)
    {
        $data = array_map(function ($element) {
            return $element += ['created_at' => Carbon::now()->format('Y-m-d H:i:s')];
        }, $data);
        $insertedCount = $this->model->insert($data);
        if ($insertedCount != count($data)) {
            throw new UnknownException(__('Unknown error: Could not insert data!'));
        };

        return $insertedCount;
    }

    public function insertAndRetrieve(array $data)
    {
        $models = [];
        DB::beginTransaction();
        try {
            foreach ($data as $item) {
                $models[] = $this->model->create($item);
            }
            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }

        return $models;
    }

    public function update(array $data, $id, $userId = null)
    {
        $model = $this->selectById($id);

        if (isset($data['config_json'])) {
            try {
                $data['config_json'] = array_replace_recursive($model->config_json, $data['config_json']);
            } catch (\Throwable $th) {
                //logger()->info("Couldn't merge config_json with existing config_json", $th->getTrace());
            }
        }
        $updateData = [];
        foreach($data as $key=>$value){
            if(!is_null($data[$key])){
                $updateData[$key] = $value;
            }
            if($key == "patronymic_name"){
                $updateData[$key] = $value;
            }
            if($key == "service_group_id"){
                $updateData[$key] = $value;
            }
        }
        $model->update($updateData);

        return $model;
    }

    public function updateByModel(array $data, $model): Model
    {
        if (isset($data['config_json'])) {
            try {
                $data['config_json'] = array_replace_recursive($model->config_json, $data['config_json']);
            } catch (\Throwable $th) {
                //logger()->info("Couldn't merge config_json with existing config_json", $th->getTrace());
            }
        }
        $updateData = [];
        foreach($data as $key=>$value){
            if(!is_null($data[$key])){
                $updateData[$key] = $value;
            }
        }

        $model->update($updateData);
        return $model;
    }

    public function delete($id)
    {
        $model = null;
        if ($model = $this->model->where('id', $id)->first()->delete() == 0) {
            throw new ModelNotFoundException();
        }
        return $model;
    }

    public function deleteMany(array $ids)
    {
        return $this->model->whereIn('id', $ids)->delete();
    }

    public function deleteByModel($model)
    {
        return (bool) $model->delete();
    }

    public function updateMany(array $data, array $ids = [])
    {
        $model = $this->model;

        if (!empty($ids)) {
            $model->whereIn('id', $ids);
        }

        $model->update($data);
        return $model;
    }
}
