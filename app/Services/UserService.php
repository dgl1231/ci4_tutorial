<?php

namespace App\Services;

use App\Interfaces\Users\UserServiceInterface;
use App\Interfaces\Users\UserRepositoryInterface;
use App\Interfaces\Auth\pbkdf2Interface;

class UserService implements UserServiceInterface
{
    protected $userRepo;
    protected $pbkdf;
    public function __construct(UserRepositoryInterface $userRepo, pbkdf2Interface $pbkdf)
    {
        $this->userRepo = $userRepo;
        $this->pbkdf = $pbkdf;
    }
    public function create(array $data)
    {
        $password = $data['password'];
        $data['password'] = $this->pbkdf->create_hash((string) $password);
        $this->userRepo->create($data);
    }
    public function search()
    {
        return $this->userRepo->findAllUsers();
    }

    public function login(array $data)
    {
        $username = $data['username'];
        $password = $data['password'];
        $userData = $this->userRepo->findByUsername($username);
        log_message('info', $userData->email);
        log_message('info', $userData->password);

        $hash = $userData->password;
        $result = $this->pbkdf->validate_password($password, $hash);
        return $result;
    }
    public function update(int $id, array $data)
    {
        return $this->userRepo->update($id, $data);
    }

    public function delete(int $id)
    {
        return $this->userRepo->delete($id);
    }

    public function getMyProfile()
    {

    }
}
?>