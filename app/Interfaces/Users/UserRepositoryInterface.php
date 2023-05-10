<?php

namespace App\Interfaces\Users;

interface UserRepositoryInterface
{
    public function findAllUsers();

    public function findById(string $email);

    public function findByEmail(string $email);

    public function create(array $data);
    public function findByUsername(string $username);


    public function update(int $id, array $data);

    public function delete(int $id);

}

?>