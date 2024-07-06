<?php
namespace Imefisto\AuthLib\UseCases\SignUp;

use Imefisto\AuthLib\Domain\BasicRoles;
use Imefisto\AuthLib\Domain\Exceptions\RoleNotAdmittedException;
use Imefisto\AuthLib\Domain\Role;
use Imefisto\AuthLib\Domain\RoleList;
use Imefisto\AuthLib\Domain\User;

class SignUpUserFactory
{
    public function __construct(
        private Role $defaultRole = BasicRoles::User,
        private ?RoleList $admittedRoles = null
    ) {
        $this->admittedRoles = $admittedRoles
            ?? (new RoleList())->addRole(BasicRoles::User);
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
            $role = $this->defaultRole::from($request->getRole());
            if ($this->admittedRoles->contains($role)) {
                return $role;
            } else {
                throw new RoleNotAdmittedException($role->getValue());
            }
        }

        return $this->defaultRole;
    }
}
