<?php
namespace Imefisto\AuthLib\UseCases\Login;

interface LoginOutputPort
{
    public function userLoggedIn(LoginResponse $response): void;
    public function userNotFound(): void;
}