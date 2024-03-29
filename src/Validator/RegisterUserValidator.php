<?php

declare(strict_types=1);

namespace App\Validator;

use App\Bundle\Error;
use App\Core\{Manager, Token};
use App\Repository\UserRepository;

class RegisterUserValidator extends Error
{
    protected Token $csrfToken;
    protected Manager $rm;

    public function __construct(Token $csrfToken, Manager $rm)
    {
        parent::__construct();

        $this->csrfToken = $csrfToken;
        $this->rm = $rm;
    }

    public function validate(
        string $login,
        string $email,
        string $repeatEmail,
        string $password,
        string $repeatPassword,
        string $token
    ): void {
        $ur = $this->rm->getRepository(UserRepository::class);

        if ($login !== '' && $ur->isUserLogin($login)) {
            $this->addError('An account with the given login already exists.');
        }

        if (strlen($login) < 3) {
            $this->addError('Your login can be at least 3 characters long.');
        } elseif (strlen($login) > 20) {
            $this->addError('Your login can be up to 20 characters long.');
        }

        if (preg_match('/^([0-9A-Za-z]*)$/', $login) !== 1) {
            $this->addError(
                'Your login can only consist of letters and numbers.'
            );
        }

        if ($email !== '' && $ur->isUserEmail($email)) {
            $this->addError('An account with the given email already exists.');
        }

        if (strlen($email) > 100 || strlen($repeatEmail) > 100) {
            $this->addError('Your email can be up to 100 characters long.');
        }

        if (
            preg_match(
                '/^([0-9A-Za-z._-]+)@([0-9A-Za-z-]+\.)+([0-9A-Za-z]{1,63})$/',
                $email
            ) !== 1
        ) {
            $this->addError(
                'Your email must be in the following format: name@domain.com'
            );
        } elseif (
            preg_match(
                '/^([0-9A-Za-z._-]+)@([0-9A-Za-z-]+\.)+([0-9A-Za-z]{1,63})$/',
                $repeatEmail
            ) !== 1
        ) {
            $this->addError(
                'Your email must be in the following format: name@domain.com'
            );
        }

        if ($email !== $repeatEmail) {
            $this->addError('This email and the repeated email do not match.');
        }

        if (strlen($password) < 8 || strlen($repeatPassword) < 8) {
            $this->addError(
                'Your password can be at least 8 characters long.'
            );
        } elseif (strlen($password) > 30 || strlen($repeatPassword) > 30) {
            $this->addError('Your password can be up to 30 characters long.');
        }

        if (preg_match('/^([!@#$%^&*()0-9A-Za-z]*)$/', $password) !== 1) {
            $this->addError(
                'Your password can only consist of letters and numbers.'
            );
        } elseif (
            preg_match(
                '/^([!@#$%^&*()0-9A-Za-z]*)$/',
                $repeatPassword
            ) !== 1
        ) {
            $this->addError(
                'Your password can only consist of letters and numbers.'
            );
        }

        if ($password !== $repeatPassword) {
            $this->addError(
                'This password and the repeated password do not match.'
            );
        }

        if ($token !== $this->csrfToken->receiveToken()) {
            $this->addError('The transmitted data token is invalid.');
        }
    }
}
