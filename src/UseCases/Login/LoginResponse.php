<?php
namespace Imefisto\AuthLib\UseCases\Login;

use Imefisto\AuthLib\Domain\UserId;

class LoginResponse
{
    public function __construct(public readonly UserId $userId)
    {
    }
}
