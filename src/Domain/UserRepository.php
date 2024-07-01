<?php
namespace Imefisto\AuthLib\Domain;

interface UserRepository
{
    public function findByUsername(string $username): ?User;
    public function existsByUsername(string $username): bool;
    public function createUser(User $user): UserId;
}
