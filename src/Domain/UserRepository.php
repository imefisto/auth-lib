<?php
namespace Imefisto\AuthLib\Domain;

interface UserRepository
{
    public function existsByUsername(string $username): bool;
    public function createUser(string $username, string $password): UserId;
}
