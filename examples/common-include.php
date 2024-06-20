<?php
use Imefisto\AuthLib\Domain\ValidationResult;
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
    };
}
