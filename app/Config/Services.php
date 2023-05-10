<?php

namespace Config;

use App\Services\pbkdf2;
use App\Services\UserService;
use App\Interfaces\Users\UserServiceInterface;
use App\Repositories\UserRepository;
use App\Interfaces\Users\UserRepositoryInterface;
use App\Interfaces\Auth\pbkdf2Interface;
use App\Controllers\UserController;

use App\Models\UserModel;

use CodeIgniter\Config\BaseService;

/**
 * Services Configuration file.
 *
 * Services are simply other classes/libraries that the system uses
 * to do its job. This is used by CodeIgniter to allow the core of the
 * framework to be swapped out easily without affecting the usage within
 * the rest of your application.
 *
 * This file holds any application-specific services, or service overrides
 * that you might need. An example has been included with the general
 * method format you should use for your service methods. For more examples,
 * see the core Services file at system/Config/Services.php.
 */
class Services extends BaseService
{
    public static function inject()
    {
        $builder = new \DI\ContainerBuilder();
        $container = $builder->build();

        // UserRepository 인터페이스를 UserRepository 클래스와 바인딩합니다.
        $container->set(UserRepositoryInterface::class, function () {
            return new UserRepository(new UserModel());
        });

        // UserService 인터페이스를 UserService 클래스와 바인딩하고 UserRepositoryInterface 의존성 주입합니다.
        $container->set(UserServiceInterface::class, function () use ($container) {
            return new UserService($container->get(UserRepositoryInterface::class), $container->get(pbkdf2Interface::class));
        });

        $container->set(pbkdf2Interface::class, function () {
            return new pbkdf2();
        });

        // 생성된 컨테이너를 반환합니다.
        return $container;
    }

}