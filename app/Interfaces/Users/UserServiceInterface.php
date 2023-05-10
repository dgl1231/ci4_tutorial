<?php

namespace App\Interfaces\Users;

interface UserServiceInterface
{
    public function create(array $data);

    public function search();

    public function update(int $id, array $data);

    public function login(array $data);

    public function delete(int $id);

    public function getMyProfile();
}
?>