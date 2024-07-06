<?php
namespace Imefisto\AuthLib\UseCases\Login;

interface LoginOutputPort
{
    public function userLoggedIn(LoginResponse $response): void;
    public function userNotFound(): void;
    public function passwordNotMatch(): void;
    public function roleNotAdmitted(string $role): void;
}
