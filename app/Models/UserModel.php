<?php
namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table = 'user';
    protected $primaryKey = 'id';

    protected $allowedFields = ['username', 'email', 'password'];

    // protected $useTimestamps = true;
    // protected $createdField = 'created_at';
    // protected $updatedField = 'updated_at';
    // protected $deletedField = 'deleted_at';

    protected $validationRules = [
        'username' => 'required|min_length[3]|max_length[20]',
        'email' => 'required|valid_email|is_unique[user.email]',
        'password' => 'required',
    ];
    protected $validationMessages = [
        'username' => [
            'required' => 'ID는 필수값',
            'min_length' => 'length제한 확인',
            'max_length' => 'length제한 확인'
        ],
        'email' => [
            'required' => 'email은 필수값',
            'valid_email' => 'email형식이 다름',
            'is_unique' => '유니크해야함'
        ],
        'password' => [
            'required' => 'password는 필수값',
        ]
    ];
    protected $skipValidation = false;

}
?>