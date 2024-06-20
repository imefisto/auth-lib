<?php
namespace Imefisto\AuthLib\Infrastructure\Persistence;

use Imefisto\AuthLib\Domain\UserId;
use Imefisto\AuthLib\Domain\UserRepository;

class InMemoryUserRepository implements UserRepository
{
    public function __construct(private array $users = [])
    {
    }

    public function existsByUsername(string $username): bool
    {
        return isset($this->users[$username]);
    }

    public function createUser(string $username, string $password): UserId
    {
        $this->users[$username] = $password;
        return new UserId($username);
    }
}
