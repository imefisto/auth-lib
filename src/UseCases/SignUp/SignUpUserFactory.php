<?php
namespace Imefisto\AuthLib\UseCases\SignUp;

use Imefisto\AuthLib\Domain\User;

class SignUpUserFactory
{
    public function createUserFromRequest(
        SignUpRequest $request
    ): User {
        return (new User($request->username))
            ->hashPassword($request->password);
    }
}
