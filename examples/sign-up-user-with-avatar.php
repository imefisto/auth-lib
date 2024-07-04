<?php
namespace Imefisto\AuthLib\Examples;

use Imefisto\AuthLib\Domain\User;
use Imefisto\AuthLib\Domain\ValidationResult;
use Imefisto\AuthLib\Infrastructure\Persistence\InMemoryUserRepository;
use Imefisto\AuthLib\UseCases\SignUp\SignUpInteractor;
use Imefisto\AuthLib\UseCases\SignUp\SignUpRequest;
use Imefisto\AuthLib\UseCases\SignUp\SignUpUserFactory;
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

        // validate that avatar is an url
        if (!filter_var($request->avatar, FILTER_VALIDATE_URL)) {
            $validationResult->addError(
                'avatar',
                "{$request->avatar} is not a valid URL"
            );
        }

        return $validationResult;
    }
};

$factory = new class extends SignUpUserFactory {
    public function createUserFromRequest(SignUpRequest $request): User
    {
        return new User(
            $request->username,
            $request->password,
            $request->avatar
        );
    }
};

$signUpUseCase = new SignUpInteractor(
    $userRepository,
    $presenter,
    $validator,
    $factory
);

$validRequest = new SignUpRequestWithAvatar (
    'user@example.com',
    'some-password',
    'https://example.com/avatar.jpg'
);

$signUpUseCase->signUp($validRequest);
