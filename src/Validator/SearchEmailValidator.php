<?php

declare(strict_types=1);

namespace App\Validator;

use App\Bundle\Error;
use App\Core\Token;

class SearchEmailValidator extends Error
{
    protected Token $csrfToken;

    public function __construct(Token $csrfToken)
    {
        parent::__construct();

        $this->csrfToken = $csrfToken;
    }

    public function validate(int $list, string $email, string $token): void
    {
        if ($list === 0) {
            $this->addError('No email list is selected.');
        }

        if ($email === '') {
            $this->addError('Enter your email.');
        }

        if ($token !== $this->csrfToken->receiveToken()) {
            $this->addError('The transmitted data token is invalid.');
        }
    }
}
