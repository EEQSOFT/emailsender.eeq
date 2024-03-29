<?php

declare(strict_types=1);

namespace App\Validator;

use App\Bundle\Error;
use App\Core\{Manager, Token};
use App\Repository\ListRepository;

class AddEmailListValidator extends Error
{
    protected Token $csrfToken;
    protected Manager $rm;

    public function __construct(Token $csrfToken, Manager $rm)
    {
        parent::__construct();

        $this->csrfToken = $csrfToken;
        $this->rm = $rm;
    }

    public function validate(string $name, string $token): void
    {
        $lr = $this->rm->getRepository(ListRepository::class);

        if ($name !== '' && $lr->isListName($name)) {
            $this->addError('A list with the given name already exists.');
        }

        if (strlen($name) < 1) {
            $this->addError(
                'Your list name can be at least 1 character long.'
            );
        } elseif (strlen($name) > 100) {
            $this->addError(
                'Your list name can be up to 100 characters long.'
            );
        }

        if ($token !== $this->csrfToken->receiveToken()) {
            $this->addError('The transmitted data token is invalid.');
        }
    }
}
