<?php
namespace Imefisto\AuthLib\Domain;

class UserId
{
    public function __construct(private string $id)
    {
    }

    public function __toString(): string
    {
        return $this->id;
    }
}
