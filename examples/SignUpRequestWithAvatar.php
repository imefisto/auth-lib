<?php
namespace Imefisto\AuthLib\Examples;

use Imefisto\AuthLib\UseCases\SignUp\SignUpRequest;

class SignUpRequestWithAvatar extends SignUpRequest
{
    public string $avatar;

    public function __construct(
        string $username,
        string $password,
        string $avatar
    ) {
        parent::__construct($username, $password);
        $this->avatar = $avatar;
    }
}
