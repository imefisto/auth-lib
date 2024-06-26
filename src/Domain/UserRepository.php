<?php
namespace Imefisto\AuthLib\Domain;

interface UserRepository
{
    public function existsByUsername(string $username): bool;
    public function createUser(User $user): UserId;
}
