<?php

declare(strict_types=1);

namespace App\Core;

class Token
{
    public function generateToken(): string
    {
        $token = '';

        for ($i = 0; $i < 100; $i++) {
            if (rand(0, 2) !== 0) {
                $j = rand(0, 51);
                $token .= substr(
                    'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz',
                    $j,
                    1
                );
            } else {
                $j = rand(0, 23);
                $token .= substr(
                    '1234567890!@#$%^*()[]{}?',
                    $j,
                    1
                );
            }
        }

        $_SESSION['token'] = $token;

        return $token;
    }

    public function receiveToken(): string
    {
        $token = $_SESSION['token'] ?? $this->generateToken();

        $this->generateToken();

        return $token;
    }

    public function receiveTokenOnly(): string
    {
        return $_SESSION['token'] ?? $this->generateToken();
    }
}
