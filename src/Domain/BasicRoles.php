<?php
namespace Imefisto\AuthLib\Domain;

enum BasicRoles: string implements Role {
    case Admin = 'admin';
    case User = 'user';

    public function getValue(): string {
        return $this->value;
    }
}
