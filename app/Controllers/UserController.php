<?php
namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;

use App\Interfaces\Users\UserServiceInterface;

use CodeIgniter\Log\Logger;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

$autoload['libraries'] = array('form_validation');

class UserController extends ResourceController
{
    private $userService;
    protected $logger;

    protected $helpers = ['form'];
    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
    {
        parent::initController($request, $response, $logger);
        // 컨테이너를 생성하고, UserServiceInterface 인터페이스를 주입받아 인스턴스를 생성합니다.
        $container = \CodeIgniter\Config\Services::inject();
        $this->userService = $container->get(UserServiceInterface::class);

        $this->logger = service(Logger::class);
    }

    public function index()
    {
        $users = $this->userService->search();
        return $this->respond($users);
    }

    // Method   : POST
    // Url      : /register
    public function create()
    {
        // Form Validation 룰 정의
        $rules = [
            'username' => [
                'label' => 'ID',
                'rules' => 'required|min_length[3]|max_length[20]',
                'errors' => [
                    'required' => '{field}는 필수 입력 항목입니다.',
                    'min_length' => '{field}는 최소 {param}자 이상 입력해야 합니다.',
                    'max_length' => '{field}는 최대 {param}자까지 입력할 수 있습니다.',
                ],
            ],
            'email' => [
                'label' => '이메일',
                'rules' => 'required|valid_email|is_unique[user.email]',
                'errors' => [
                    'required' => '{field}은 필수 입력 항목입니다.',
                    'valid_email' => '유효한 이메일 주소를 입력해주세요.',
                    'is_unique' => '입력하신 {field}는 이미 사용 중인 이메일 주소입니다.',
                ],
            ],
            'password' => [
                'label' => '비밀번호',
                'rules' => 'required|min_length[6]',
                'errors' => [
                    'required' => '{field}는 필수 입력 항목입니다.',
                    'min_length' => '{field}는 최소 {param}자 이상 입력해야 합니다.',
                ],
            ],
        ];

        // Form Validation 룰 적용
        if (!$this->validate($rules)) {
            // 검증 실패 시 처리할 코드
            $validation = \Config\Services::validation();
            throw new \RuntimeException($validation->getErrors());
        }

        $data = [
            'username' => $this->request->getVar('username'),
            'email' => $this->request->getVar('email'),
            'password' => $this->request->getVar('password')
        ];

        $log_message = json_encode($data);
        log_message('info', $log_message);

        try {
            $this->userService->create($data);
            return redirect()->to('/');
        } catch (\Exception $e) {
            return $this->respond($e->getMessage(), 500);
        }
    }

    // Method   : GET
    // Url      : /register
    public function createuser()
    {
        $data = [];
        $data['validation'] = \Config\Services::validation();
        return view('create', $data);
    }

    // Method   : POST
    // Url      : /login
    public function login()
    {
        $data = [
            'username' => $this->request->getVar('username'),
            'password' => $this->request->getVar('password')
        ];
        try {
            $result = $this->userService->login($data);
            return $result ? redirect()->to('/') : redirect()->to('/users/createuser');
        } catch (\Exception $e) {
            return $this->respond($e->getMessage(), 500);
        }
    }
    // Method   : GET
    // Url      : /loginuser
    public function loginuser()
    {
        $data = [];
        $data['validation'] = \Config\Services::validation();
        return view('login', $data);
    }



    // Method   : PUT
    // Url      : /user/{id} 
    public function update($id = null)
    {
        $data = [
            'username' => $this->request->getVar('username'),
            'email' => $this->request->getVar('email'),
            'password' => $this->request->getVar('password')
        ];
        $this->userService->update($id, $data);
        return $this->respondUpdated();
    }

    // Method   : DELETE
    // Url      : /user/delete/{id} 
    public function delete($id = null)
    {
        $this->userService->delete($id);
        return $this->respondDeleted();
    }


    // Method   : GET
    // Url      : /user/me
    public function getProfile()
    {
        $users = $this->userService->search();
        return $this->respond($users);
    }
}