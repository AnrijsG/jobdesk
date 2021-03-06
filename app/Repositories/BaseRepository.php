<?php

namespace App\Repositories;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

abstract class BaseRepository
{
    abstract protected function tableName(): string;

    abstract protected function model(): string;

    protected function buildFindQuery()
    {
        return DB::table($this->tableName());
    }

    /**
     * @return Collection|static[]
     */
    protected function all()
    {
        return $this->model()::all();
    }

    public function saveObject(Model $obj)
    {
        return $obj->save();
    }
}
