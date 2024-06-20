<?php
namespace Imefisto\AuthLib\UseCases\SignUp;

interface SignUpOutputPort
{
    public function userSignedUp(SignUpResponse $response): void;
    public function userAlreadyExists(): void;
}
