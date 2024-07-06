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
            ->setRole($this->getRole());
    }

    private function getRole(): Role
    {
        return $this->defaultRole;
    }
}
