<?php
namespace Imefisto\AuthLib\Examples;

use Imefisto\AuthLib\Domain\User;

class UserWithAvatar extends User
{
    public function __construct(
        private string $username,
        private string $password,
        private string $avatar
    ) {
    }
}
