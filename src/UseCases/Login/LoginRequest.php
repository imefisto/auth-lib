<?php
namespace Imefisto\AuthLib\UseCases\Login;

class LoginRequest
{
    public function __construct(
        public readonly string $username,
        public readonly string $password
    ) {
    }
}
