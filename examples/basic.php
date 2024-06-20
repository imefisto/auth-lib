<?php
use Imefisto\AuthLib\Infrastructure\Persistence\InMemoryUserRepository;
use Imefisto\AuthLib\UseCases\SignUp\SignUpInteractor;
use Imefisto\AuthLib\UseCases\SignUp\SignUpOutputPort;
use Imefisto\AuthLib\UseCases\SignUp\SignUpRequest;
use Imefisto\AuthLib\UseCases\SignUp\SignUpResponse;

require_once __DIR__ . '/../vendor/autoload.php';

$userRepository = new InMemoryUserRepository();

// Create the presenter
$presenter = new class implements SignUpOutputPort {
    public function userSignedUp(SignUpResponse $response): void
    {
        echo "User signed up with ID: {$response->userId}\n";
    }

    public function userAlreadyExists(string $username): void
    {
        echo "User already exists: $username\n";
    }

    public function invalidUsername(string $username): void
    {
        echo "Invalid username: $username\n";
    }
};

$signUpUseCase = new SignUpInteractor($userRepository, $presenter);
$request = new SignUpRequest('user@example.com', 'securepassword');

// expects User signed up with ID: user@example.com
$signUpUseCase->signUp($request);

// expects User already exists: user@example.com
$signUpUseCase->signUp($request);

$invalidUsernameRequest = new SignUpRequest('userexample', 'securepassword');
$signUpUseCase->signUp($invalidUsernameRequest);
// expects Invalid username: userexample
