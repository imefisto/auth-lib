<?php
use Imefisto\AuthLib\Domain\ValidationResult;
use Imefisto\AuthLib\Infrastructure\Persistence\InMemoryUserRepository;
use Imefisto\AuthLib\UseCases\SignUp\SignUpInteractor;
use Imefisto\AuthLib\UseCases\SignUp\SignUpRequest;

require_once __DIR__ . '/../vendor/autoload.php';

$userRepository = new InMemoryUserRepository();
require_once __DIR__ . '/common-include.php';

// Create the presenter
$presenter = buildPresenterImplementation();

$signUpUseCase = new SignUpInteractor($userRepository, $presenter);
$request = new SignUpRequest('user@example.com', 'securepassword');

// expects User signed up with ID: user@example.com
$signUpUseCase->signUp($request);

// expects User already exists: user@example.com
$signUpUseCase->signUp($request);

$invalidUsernameRequest = new SignUpRequest('userexample', 'securepassword');
$signUpUseCase->signUp($invalidUsernameRequest);
// expects Invalid username: userexample
