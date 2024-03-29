<?php

declare(strict_types=1);

namespace App\Validator;

use App\Bundle\Error;
use App\Core\{Manager, Token};
use App\Repository\EmailRepository;

class InsertEmailValidator extends Error
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
        int $list,
        string $name,
        string $email,
        string $token
    ): void {
        $er = $this->rm->getRepository(EmailRepository::class);

        if ($list === 0) {
            $this->addError('No email list is selected.');
        }

        if (strlen($name) < 1) {
            $this->addError('Your name can be at least 1 character long.');
        } elseif (strlen($name) > 100) {
            $this->addError('Your name can be up to 100 characters long.');
        }

        if ($email !== '' && $er->isEmailEmail($list, $email)) {
            $this->addError('This email already exists in the selected list.');
        }

        if (strlen($email) > 100) {
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
        }

        if ($token !== $this->csrfToken->receiveToken()) {
            $this->addError('The transmitted data token is invalid.');
        }
    }
}
