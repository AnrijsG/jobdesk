<?php


namespace App\Repositories;


use Illuminate\Support\Facades\DB;

abstract class BaseRepository
{
    abstract protected function tableName(): string;

    protected function all($conditions = [], bool $isLikeExpression = false)
    {
        if ($isLikeExpression) {
            return DB::table($this->tableName())->where($conditions[0], $conditions[1], $conditions[2]);
        }

        return DB::table($this->tableName())->where($conditions);
    }
}
