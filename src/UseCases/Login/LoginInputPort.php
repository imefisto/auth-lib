<?php
namespace Imefisto\AuthLib\UseCases\Login;

interface LoginInputPort
{
    public function login(LoginRequest $request): void;
}
