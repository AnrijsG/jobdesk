<?php


namespace App\Repositories;


use Illuminate\Support\Facades\DB;

abstract class BaseRepository
{
    abstract protected function tableName(): string;

    protected function all()
    {
        return DB::table($this->tableName());
    }
}
