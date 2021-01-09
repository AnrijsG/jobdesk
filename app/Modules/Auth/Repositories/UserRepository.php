<?php

namespace App\Modules\Auth\Repositories;

use App\Models\User;
use App\Repositories\BaseRepository;

class UserRepository extends BaseRepository
{
    public function tableName(): string
    {
        return 'users';
    }

    public function getUserByEmail(string $email)
    {
        return $this->buildFindQuery()->where(['email' => $email])->get()->all()[0] ?? null;
    }

    public function getById(int $userId): ?User
    {
        return $this->all()->where('id', $userId)->first();
    }

    protected function model(): string
    {
        return User::class;
    }
}
