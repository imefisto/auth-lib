<?php
use Imefisto\AuthLib\Domain\ValidationResult;
use Imefisto\AuthLib\Infrastructure\Persistence\InMemoryUserRepository;
use Imefisto\AuthLib\UseCases\SignUp\SignUpInteractor;
use Imefisto\AuthLib\UseCases\SignUp\SignUpRequest;
use Imefisto\AuthLib\UseCases\SignUp\SignUpValidator;

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/common-include.php';

$userRepository = new InMemoryUserRepository();

// Create the presenter
$presenter = buildPresenterImplementation();

$validator = new class implements SignUpValidator {
    public function validate(SignUpRequest $request): ValidationResult
    {
        $validationResult = new ValidationResult();

        if (!filter_var($request->username, FILTER_VALIDATE_EMAIL)) {
            $validationResult->addError(
                'username',
                "{$request->username} is not a valid email"
            );
        }

        // validate that passwords must contains letters, numbers and some special characters
        if (!preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[^a-zA-Z\d])/', $request->password)) {
            $validationResult->addError(
                'password',
                'Password must contain at least one lowercase letter, one uppercase letter, one number and one special character'
            );
        }

        return $validationResult;
    }
};

$signUpUseCase = new SignUpInteractor($userRepository, $presenter, $validator);
$request1 = new SignUpRequest('user@example.com', '$eCurepa55w0rd');
$signUpUseCase->signUp($request1);
// prints User signed up with ID: user@example.com

$request2 = new SignUpRequest('user@example.com', 'insecurepassword');
$signUpUseCase->signUp($request2);
// prints Password must contain at least one lowercase letter, one uppercase letter, one number and one special character

$request3 = new SignUpRequest('userexample', 'insecurepassword');
$signUpUseCase->signUp($request3);
// prints 2 lines:
// userexample is not a valid email
// Password must contain at least one lowercase letter, one uppercase letter, one number and one special character
