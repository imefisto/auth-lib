<?php
namespace Imefisto\AuthLib\UseCases\SignUp;

use Imefisto\AuthLib\Domain\UserId;

class SignUpResponse
{
    public function __construct(public readonly UserId $userId)
    {
    }
}
