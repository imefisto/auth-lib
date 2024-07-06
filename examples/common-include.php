<?php
use Imefisto\AuthLib\Domain\ValidationResult;
use Imefisto\AuthLib\UseCases\Login\LoginOutputPort;
use Imefisto\AuthLib\UseCases\Login\LoginResponse;
use Imefisto\AuthLib\UseCases\SignUp\SignUpOutputPort;
use Imefisto\AuthLib\UseCases\SignUp\SignUpResponse;

function buildPresenterImplementation() {
    return new class implements SignUpOutputPort {
        public function userSignedUp(SignUpResponse $response): void
        {
            echo "User signed up with ID: {$response->userId}\n";
        }

        public function userAlreadyExists(string $username): void
        {
            echo "User already exists: $username\n";
        }

        public function invalidData(ValidationResult $validation): void
        {
            foreach ($validation->getFlatListOfErrors() as $error) {
                echo "$error\n";
            }
        }

        public function roleNotAdmitted(string $role): void
        {
            echo "Role not admitted: $role\n";
        }
    };
}

function buildLoginPresenterImplementation() {
    return new class implements LoginOutputPort {
        public function userLoggedIn(LoginResponse $response): void
        {
            echo "User logged in with ID: {$response->userId}\n";
        }

        public function userNotFound(): void
        {
            echo "User not found\n";
        }

        public function passwordNotMatch(): void
        {
            echo "Password does not match\n";
        }
    };
}
