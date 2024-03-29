<?php

declare(strict_types=1);

namespace App\Bundle;

class Error
{
    protected string $error;

    public function __construct()
    {
        $this->error = '';
    }

    public function addError(string $error): void
    {
        $this->error .= $error . "\n";
    }

    protected function setError(string $error): void
    {
        $this->error = $error;
    }

    public function getError(): ?array
    {
        $length = strlen($this->error);

        if ($length >= 1) {
            return explode("\n", substr($this->error, 0, ($length - 1)));
        }

        return null;
    }

    public function getStrError(): string
    {
        return $this->error;
    }

    public function isError(): bool
    {
        return ($this->error !== '') ? true : false;
    }

    public function isValid(): bool
    {
        return ($this->error === '') ? true : false;
    }
}
