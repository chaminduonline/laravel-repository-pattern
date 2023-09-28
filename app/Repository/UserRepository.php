<?php

namespace App\Repository;

use App\Interface\UserRepositoryInterface;
use App\Models\User;

class UserRepository extends BaseRepository implements UserRepositoryInterface
{

    public function __construct(User $model)
    {
        parent::__construct($model);
    }

    public function createUser($user)
    {
        return $this->save($user);
    }
    public function updateUser($id, $user)
    {
        return $this->update($user, $id);
    }
    public function findUserById($id)
    {
        return $this->find($id);
    }
    public function deleteUser($id)
    {
        return $this->delete($id);
    }
    public function findAllUsers($page, $limit = 10)
    {
        return $this->findAll([], $page);
    }
    public function findUserByEmail($email)
    {
        return $this->findOneBy(['email' => $email]);
    }
}
