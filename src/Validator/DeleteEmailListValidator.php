<?php

declare(strict_types=1);

namespace App\Validator;

use App\Bundle\Error;
use App\Core\Token;

class DeleteEmailListValidator extends Error
{
    protected Token $csrfToken;
    protected Manager $rm;

    public function __construct(Token $csrfToken)
    {
        parent::__construct();

        $this->csrfToken = $csrfToken;
    }

    public function validate(int $list, string $token): void
    {
        if ($list === 0) {
            $this->addError('No email list is selected.');
        }

        if ($token !== $this->csrfToken->receiveToken()) {
            $this->addError('The transmitted data token is invalid.');
        }
    }
}
