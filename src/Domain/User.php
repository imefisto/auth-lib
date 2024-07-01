<?php
namespace Imefisto\AuthLib\Domain;

class User
{
    private UserId $id;

    public function __construct(
        public readonly string $username,
        public readonly string $password
    ) {
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
