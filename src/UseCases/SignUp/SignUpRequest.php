<?php
namespace Imefisto\AuthLib\UseCases\SignUp;

class SignUpRequest {
    public string $username;
    public string $password;

    public function __construct(string $username, string $password)
    {
        $this->username = $username;
        $this->password = $password;
    }
}
