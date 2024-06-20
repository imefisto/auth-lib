<?php
namespace Imefisto\AuthLib\UseCases\SignUp;

use Imefisto\AuthLib\Domain\ValidationResult;

interface SignUpValidator {
    public function validate(SignUpRequest $request): ValidationResult;
}
