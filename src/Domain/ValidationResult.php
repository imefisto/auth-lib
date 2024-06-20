<?php
namespace Imefisto\AuthLib\Domain;

class ValidationResult
{
    private array $errors = [];

    public function addError(string $field, string $message): void
    {
        if (!isset($this->errors[$field])) {
            $this->errors[$field] = [];
        }

        $this->errors[$field][] = $message;
    }

    public function hasErrors(): bool
    {
        return count($this->errors) > 0;
    }

    public function getErrors(): array
    {
        return $this->errors;
    }

    public function getFlatListOfErrors(): array
    {
        $arrayOfErrors = array_values($this->errors);
        return array_merge(...$arrayOfErrors);
    }
}
