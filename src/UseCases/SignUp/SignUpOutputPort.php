<?php
namespace Imefisto\AuthLib\UseCases\SignUp;

interface SignUpOutputPort
{
    public function userSignedUp(SignUpResponse $response): void;
    public function userAlreadyExists(string $username): void;
    public function invalidUsername(string $username): void;
}
