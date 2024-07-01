<?php
namespace Imefisto\AuthLib\UseCases\Login;

use Imefisto\AuthLib\Domain\UserRepository;

class LoginInteractor implements LoginInputPort
{
    public function __construct(
        private UserRepository $userRepository,
        private LoginOutputPort $output
    ) {
    }

    public function login(LoginRequest $request): void
    {
        $user = $this->userRepository->findByUsername($request->username);
        $this->output->userLoggedIn(new LoginResponse($user->getId()));
    }
}