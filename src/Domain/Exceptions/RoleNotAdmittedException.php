<?php
namespace Imefisto\AuthLib\Domain\Exceptions;

class RoleNotAdmittedException extends \Exception
{
    public function __construct(public readonly string $role)
    {
        parent::__construct("Role $role is not admitted");
    }
}
