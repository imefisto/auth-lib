<?php
namespace Imefisto\AuthLib\UseCases\SignUp;

use Imefisto\AuthLib\Domain\ValidationResult;

interface SignUpOutputPort
{
    public function userSignedUp(SignUpResponse $response): void;
    public function userAlreadyExists(string $username): void;
    public function invalidData(ValidationResult $validation): void;
    public function roleNotAdmitted(string $role): void;
}
