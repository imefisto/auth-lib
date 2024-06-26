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

    public function existsByUsername(string $username): bool
    {
        return isset($this->users[$username]);
    }

    public function createUser(User $user): UserId
    {
        $this->users[$user->username] = $user->password;
        return new UserId($user->username);
    }
}
