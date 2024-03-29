<?php

declare(strict_types=1);

namespace App\Validator;

use App\Bundle\Error;
use App\Core\Token;

class ImportEmailsValidator extends Error
{
    protected Token $csrfToken;

    public function __construct(Token $csrfToken)
    {
        parent::__construct();

        $this->csrfToken = $csrfToken;
    }

    public function validate(
        int $list,
        string $originalFile,
        string $temporaryFile,
        int $fileError,
        string $token
    ): void {
        if ($list === 0) {
            $this->addError('No email list is selected.');
        }

        if ($originalFile === '') {
            $this->addError('A file must be selected.');
        } elseif ($temporaryFile === '') {
            switch ($fileError) {
                case 1:
                    $this->addError('The maximum file size is exceeded.');

                    break;
                default:
                    $this->addError('Your file has not been uploaded.');

                    break;
            }
        }

        if ($token !== $this->csrfToken->receiveTokenOnly()) {
            $this->addError('The transmitted data token is invalid.');
        }
    }
}
