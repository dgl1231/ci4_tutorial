<?php
namespace App\Repositories;

use App\Interfaces\Users\UserRepositoryInterface;
use App\Models\UserModel;

class UserRepository implements UserRepositoryInterface
{
    protected $userModel;

    public function __construct(UserModel $userModel)
    {
        $this->userModel = $userModel;
    }

    public function findAllUsers()
    {
        return $this->userModel->findAll();
    }
    public function findById(string $id)
    {
        return $this->userModel->find($id);
    }
    public function findByEmail(string $email)
    {
        return $this->userModel->find($email);

    }
    public function findByUsername(string $username)
    {
        $query = $this->userModel->where('username', $username)->get();
        $result = $query->getRow();
        return $result;
    }

    public function create(array $data)
    {
        if (!$this->userModel->setValidationRules($this->userModel->validationRules)->validate($data)) {
            throw new \RuntimeException(implode(' ', $this->userModel->errors()));
        }

        try {
            return $this->userModel->insert($data);

        } catch (\Exception $e) {
            exit($e->getMEssage());
        }
    }

    public function update(int $id, array $data)
    {
        $this->userModel->update($id, $data);
    }

    public function delete(int $id)
    {
        $this->userModel->delete($id);
    }
}