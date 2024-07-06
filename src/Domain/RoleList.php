<?php
namespace Imefisto\AuthLib\Domain;

class RoleList
{
    private array $roles = [];

    public function addRole(Role $role): self
    {
        $this->roles[] = $role;
        return $this;
    }

    public function contains(Role $role): bool
    {
        foreach ($this->roles as $r) {
            if ($r->getValue() == $role->getValue()) {
                return true;
            }
        }

        return false;
    }
}
