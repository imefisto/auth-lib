<?php
namespace Imefisto\AuthLib\UseCases\SignUp;

class SignUpRequest {
    private string $role = '';

    public function __construct(
        public readonly string $username,
        public readonly string $password
    ) {
    }

    public function withRole(string $role): self
    {
        $this->role = $role;
        return $this;
    }

    public function getRole(): string
    {
        return $this->role;
    }
}
