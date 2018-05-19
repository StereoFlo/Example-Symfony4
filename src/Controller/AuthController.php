<?php

namespace App\Controller;

use App\Commands\UserRegisterCommand;
use App\Repository\User\UserInterface;
use HttpInvalidParamException;
use League\Tactician\CommandBus;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;

/**
 * Class AuthController
 * @package App\Controller
 */
class AuthController
{
    /**
     * @var CommandBus
     */
    private $bus;

    /**
     * @var UserInterface
     */
    private $userRepository;

    /**
     * @var AbstractController
     */
    private $controller;

    /**
     * AuthController constructor.
     *
     * @param CommandBus         $bus
     * @param UserInterface      $userRepository
     * @param AbstractController $controller
     */
    public function __construct(CommandBus $bus, UserInterface $userRepository, AbstractController $controller)
    {
        $this->bus            = $bus;
        $this->userRepository = $userRepository;
        $this->controller     = $controller;
    }

    /**
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function register(Request $request): JsonResponse
    {
        $email    = $request->request->get('email');
        $username = $request->request->get('username');
        $password = $request->request->get('password');

        $this->bus->handle(new UserRegisterCommand($username, $email, $password));

        return $this->controller->acceptJson('registred.success');
    }

    /**
     * @param Request $request
     *
     * @return JsonResponse
     * @throws \Exception
     */
    public function login(Request $request): JsonResponse
    {
        $email    = $request->request->get('email');
        $password = $request->request->get('password');

        if (empty($email) || empty($password)) {
            throw new HttpInvalidParamException('form.input.empty');
        }

        $user = $this->userRepository->getByEmail($email);

        if (!$user) {
            throw new UnauthorizedHttpException('login.not.found');
        }

        if (!\password_verify($password, $user->getPassword())) {
            throw new UnauthorizedHttpException('invalid.password');
        }

        $this->userRepository->save($user->setLastLogin());

        return $this->controller->acceptJson('logged.in', 202, ['token' => $user->getApiToken()->getKey()]);
    }

    /**
     * @param Request $request
     *
     * @return JsonResponse
     * @throws HttpInvalidParamException
     */
    public function logout(Request $request): JsonResponse
    {
        $token = $request->request->get('token');
        if (empty($token)) {
            throw new HttpInvalidParamException('token.empty');
        }
        $user = $this->userRepository->getByToken($token);
        $user->setApiToken(null);
        $this->userRepository->save($user);

        return $this->controller->acceptJson('logged.out');
    }
}