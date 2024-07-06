<?php
namespace Imefisto\AuthLib\UseCases\SignUp;

use Imefisto\AuthLib\Domain\BasicRoles;
use Imefisto\AuthLib\Domain\Role;
use Imefisto\AuthLib\Domain\User;

class SignUpUserFactory
{
    public function __construct(
        private Role $defaultRole = BasicRoles::User
    ) {
    }

    public function createUserFromRequest(
        SignUpRequest $request
    ): User {
        return (new User($request->username))
            ->hashPassword($request->password)
            ->setRole($this->getRole($request));
    }

    private function getRole(SignUpRequest $request): Role
    {
        if ($request->getRole() !== '') {
            return $this->defaultRole::from($request->getRole());
        }

        return $this->defaultRole;
    }
}
