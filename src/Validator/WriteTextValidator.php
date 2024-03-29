<?php

declare(strict_types=1);

namespace App\Validator;

use App\Bundle\Error;
use App\Core\Token;

class WriteTextValidator extends Error
{
    protected Token $csrfToken;

    public function __construct(Token $csrfToken)
    {
        parent::__construct();

        $this->csrfToken = $csrfToken;
    }

    public function validate(
        string $subject,
        string $message,
        string $token
    ): void {
        if (strlen($subject) < 1) {
            $this->addError(
                'Your subject can be at least 1 character long.'
            );
        } elseif (strlen($subject) > 100) {
            $this->addError(
                'Your subject can be up to 100 characters long.'
            );
        }

        if (strlen($message) < 1) {
            $this->addError(
                'Your message can be at least 1 character long.'
            );
        } elseif (strlen($message) > 50000) {
            $this->addError(
                'Your message can be up to 50000 characters long.'
            );
        }

        if ($token !== $this->csrfToken->receiveToken()) {
            $this->addError('The transmitted data token is invalid.');
        }
    }
}
