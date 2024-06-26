<?php
namespace Imefisto\AuthLib\Domain;

class User
{
    public function __construct(
        public readonly string $username,
        public readonly string $password
    ) {
    }
}
