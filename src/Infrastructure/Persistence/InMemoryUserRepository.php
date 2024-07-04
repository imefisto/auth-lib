<?php
namespace Imefisto\AuthLib\Infrastructure\Persistence;

use Imefisto\AuthLib\Domain\User;
use Imefisto\AuthLib\Domain\UserId;
use Imefisto\AuthLib\Domain\UserRepository;

class InMemoryUserRepository implements UserRepository
{
    public function __construct(private array $users = [])
    {
    }

    public function findByUsername(string $username): ?User
    {
        return $this->users[$username] ?? null;
    }

    public function existsByUsername(string $username): bool
    {
        return isset($this->users[$username]);
    }

    public function createUser(User $user): UserId
    {
        $userId = new UserId(bin2hex(random_bytes(16)));
        $this->users[$user->username] = $user->setId($userId);

        return $userId;
    }
}
