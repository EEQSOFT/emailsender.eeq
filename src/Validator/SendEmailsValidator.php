<?php

declare(strict_types=1);

namespace App\Validator;

use App\Bundle\Error;
use App\Core\Token;

class SendEmailsValidator extends Error
{
    protected Token $csrfToken;

    public function __construct(Token $csrfToken)
    {
        parent::__construct();

        $this->csrfToken = $csrfToken;
    }

    public function validate(
        int $list,
        int $text,
        string $token,
        bool $send = true
    ): void {
        if ($send) {
            if (file_exists(CRONTAB_FILE)) {
                $this->addError('Stop sending your emails to send again.');
            }

            if ($list === 0) {
                $this->addError('No email list is selected.');
            }

            if ($text === 0) {
                $this->addError('No text is selected.');
            }
        }

        if ($token !== $this->csrfToken->receiveToken()) {
            $this->addError('The transmitted data token is invalid.');
        }
    }
}
