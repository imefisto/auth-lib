<?php
namespace Imefisto\AuthLib\UseCases\SignUp\Validators;

use Imefisto\AuthLib\Domain\ValidationResult;
use Imefisto\AuthLib\UseCases\SignUp\SignUpRequest;
use Imefisto\AuthLib\UseCases\SignUp\SignUpValidator;

class EmailValidator implements SignUpValidator
{
    public function validate(SignUpRequest $request): ValidationResult
    {
        $validationResult = new ValidationResult();

        if (!filter_var($request->username, FILTER_VALIDATE_EMAIL)) {
            $validationResult->addError(
                'username',
                "{$request->username} is not a valid email"
            );
        }

        return $validationResult;
    }
}
