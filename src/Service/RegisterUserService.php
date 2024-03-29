<?php

declare(strict_types=1);

namespace App\Service;

use App\Bundle\{Html, Key};
use App\Controller\RegisterUserController;
use App\Core\{Cache, Config, Email, Token};
use App\Repository\{OptionRepository, UserRepository};
use App\Validator\RegisterUserValidator;

class RegisterUserService
{
    protected RegisterUserController $registerUserController;
    protected Config $config;
    protected Cache $cache;
    protected Email $mail;
    protected Html $html;
    protected Key $key;
    protected Token $csrfToken;
    protected RegisterUserValidator $registerUserValidator;

    public function __construct(
        RegisterUserController $registerUserController,
        Config $config,
        Cache $cache,
        Email $mail,
        Html $html,
        Key $key,
        Token $csrfToken,
        RegisterUserValidator $registerUserValidator
    ) {
        $this->registerUserController = $registerUserController;
        $this->config = $config;
        $this->cache = $cache;
        $this->mail = $mail;
        $this->html = $html;
        $this->key = $key;
        $this->csrfToken = $csrfToken;
        $this->registerUserValidator = $registerUserValidator;
    }

    public function registerUserAction(
        string $login,
        string $email,
        string $repeatEmail,
        string $password,
        string $repeatPassword,
        bool $submit,
        string $token
    ): array {
        $rm = $this->registerUserController->getManager();
        $or = $rm->getRepository(OptionRepository::class);
        $ur = $rm->getRepository(UserRepository::class);

        if ($submit) {
            $this->registerUserValidator->validate(
                $login,
                $email,
                $repeatEmail,
                $password,
                $repeatPassword,
                $token
            );

            if ($this->registerUserValidator->isValid()) {
                $key = $this->key->generateKey();

                $registrationUserDataAdded = $ur->addRegistrationUserData(
                    $login,
                    $email,
                    $password,
                    $key,
                    $this->config->getRemoteAddress(),
                    $this->config->getDateTimeNow()
                );

                if ($registrationUserDataAdded) {
                    $activationEmailSent = $this->sendActivationEmail(
                        $email,
                        $login,
                        $key
                    );

                    $or->setOptionRegistered();

                    $this->cache->cacheFile(
                        OPTIONS_FILE,
                        str_replace(
                            "'registered' => false",
                            "'registered' => true",
                            file_get_contents(OPTIONS_FILE)
                        )
                    );

                    return array(
                        'content' => 'register-user/'
                            . 'account-created-info.php',
                        'activeMenu' => 'register-user',
                        'title' => 'Information',
                        'activationEmailSent' => $activationEmailSent
                    );
                } else {
                    return array(
                        'content' => 'register-user/'
                            . 'account-not-created-info.php',
                        'activeMenu' => 'register-user',
                        'title' => 'Information'
                    );
                }
            }
        }

        return array(
            'content' => 'register-user/register-user.php',
            'activeMenu' => 'register-user',
            'title' => 'User Registration',
            'error' => $this->html->prepareError(
                $this->registerUserValidator->getError()
            ),
            'login' => $login,
            'email' => $email,
            'token' => $this->csrfToken->generateToken()
        );
    }

    private function sendActivationEmail(
        string $email,
        string $login,
        string $key
    ): bool {
        return $this->mail->sendEmail(
            $this->config->getServerName(),
            $this->config->getAdminEmail(),
            $email,
            'The ' . $login . ' account activation in the '
                . $this->config->getServerDomain() . ' service',
            'To activate the account, open the url below in a browser window.'
                . "\n\n" . $this->config->getUrl() . '/activate,'
                . $login . ',' . $key . "\n\n" . '--' . "\n"
                . $this->config->getAdminEmail()
        );
    }
}
