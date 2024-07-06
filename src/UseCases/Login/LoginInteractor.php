<?php
namespace Imefisto\AuthLib\UseCases\Login;

use Imefisto\AuthLib\Domain\BasicRoles;
use Imefisto\AuthLib\Domain\RoleList;
use Imefisto\AuthLib\Domain\UserRepository;

class LoginInteractor implements LoginInputPort
{
    public function __construct(
        private UserRepository $userRepository,
        private LoginOutputPort $output,
        private ?RoleList $admittedRoles = null
    ) {
        $this->admittedRoles = $admittedRoles
            ?? (new RoleList())->addRole(BasicRoles::User);
    }

    public function login(LoginRequest $request): void
    {
        $user = $this->userRepository->findByUsername($request->username);

        if (is_null($user)) {
            $this->output->userNotFound();
            return;
        }

        if (!$this->admittedRoles->contains($user->getRole())) {
            $this->output->roleNotAdmitted($user->getRole()->value);
            return;
        }

        if (!$user->passwordMatches($request->password)) {
            $this->output->passwordNotMatch();
            return;
        }

        $this->output->userLoggedIn(new LoginResponse($user->getId()));
    }
}
