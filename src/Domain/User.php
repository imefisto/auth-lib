<?php
namespace Imefisto\AuthLib\Domain;

class User
{
    private UserId $id;

    public function __construct(
        public readonly string $username,
        private string $passwordHash = ''
    ) {
    }

    public function passwordMatches(string $password): bool
    {
        return password_verify($password, $this->passwordHash);
    }

    public function hashPassword(string $password): self
    {
        $this->passwordHash = password_hash($password, PASSWORD_DEFAULT);
        return $this;
    }

    public function getId(): UserId
    {
        return $this->id;
    }

    public function setId(UserId $id): self
    {
        $this->id = $id;
        return $this;
    }
}
