<?php

declare(strict_types=1);

namespace App\Validator;

use App\Core\Token;
use App\Bundle\Error;

class ChangePasswordValidator extends Error
{
    protected Token $csrfToken;

    public function __construct(Token $csrfToken)
    {
        parent::__construct();

        $this->csrfToken = $csrfToken;
    }

    public function validate(
        string $newPassword,
        string $repeatPassword,
        string $token
    ): void {
        if (strlen($newPassword) < 8 || strlen($repeatPassword) < 8) {
            $this->addError(
                'Your password can be at least 8 characters long.'
            );
        } elseif (strlen($newPassword) > 30 || strlen($repeatPassword) > 30) {
            $this->addError('Your password can be up to 30 characters long.');
        }

        if (preg_match('/^([!@#$%^&*()0-9A-Za-z]*)$/', $newPassword) !== 1) {
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

        if ($newPassword === '' || $repeatPassword === '') {
            $this->addError(
                'A new password or a repeated password has not been entered.'
            );
        }

        if ($newPassword !== $repeatPassword) {
            $this->addError(
                'This new password and the repeated password do not match.'
            );
        }

        if ($token !== $this->csrfToken->receiveToken()) {
            $this->addError('The transmitted data token is invalid.');
        }
    }
}
