<?php

declare(strict_types=1);

namespace App\Validator;

use App\Bundle\Error;
use App\Core\Token;

class AppOptionsValidator extends Error
{
    protected Token $csrfToken;

    public function __construct(Token $csrfToken)
    {
        parent::__construct();

        $this->csrfToken = $csrfToken;
    }

    public function validate(
        string $adminEmail,
        int $emailsNumber,
        int $timePeriod,
        string $unsubscribeUrl,
        string $newsletterName,
        string $token
    ): void {
        if (strlen($adminEmail) > 100) {
            $this->addError('Your email can be up to 100 characters long.');
        }

        if (
            preg_match(
                '/^([0-9A-Za-z._-]+)@([0-9A-Za-z-]+\.)+([0-9A-Za-z]{1,63})$/',
                $adminEmail
            ) !== 1
        ) {
            $this->addError(
                'Your email must be in the following format: name@domain.com'
            );
        }

        if ($emailsNumber < 1) {
            $this->addError(
                'You cannot send less than 1 email in one package.'
            );
        } elseif ($emailsNumber > 50) {
            $this->addError(
                'Sending more than 50 emails in one package can be '
                    . 'considered spam.'
            );
        }

        if ($timePeriod < 10) {
            $this->addError(
                'Sending more frequently than every 10 minutes can be '
                    . 'considered spam.'
            );
        } elseif ($timePeriod > 1440) {
            $this->addError(
                'You cannot send less frequently than every 24 hours.'
            );
        }

        if (
            substr($unsubscribeUrl, 0, 7) !== 'http://'
            && substr($unsubscribeUrl, 0, 8) !== 'https://'
        ) {
            $this->addError('Your unsubscribe url must start with: http://');
        }

        if (strlen($unsubscribeUrl) > 100) {
            $this->addError(
                'Your unsubscribe url can be up to 100 characters long.'
            );
        }

        if (strlen($newsletterName) < 1) {
            $this->addError(
                'Your newsletter name can be at least 1 character long.'
            );
        } elseif (strlen($newsletterName) > 100) {
            $this->addError(
                'Your newsletter name can be up to 100 characters long.'
            );
        }

        if ($token !== $this->csrfToken->receiveToken()) {
            $this->addError('The transmitted data token is invalid.');
        }
    }
}
