<?php
namespace Imefisto\AuthLib\UseCases\SignUp;

interface SignUpInputPort
{
    public function signUp(SignUpRequest $request): void;
}
