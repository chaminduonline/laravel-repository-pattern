<?php
namespace App\Interface;

interface UserRepositoryInterface{
    public function createUser($user);
    public function updateUser($id,$user);
    public function findUserById($id);
    public function findUserByEmail($email);
    public function deleteUser($id);
    public function findAllUsers($page,$limit = 10);
}