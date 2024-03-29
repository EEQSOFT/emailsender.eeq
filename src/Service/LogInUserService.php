<?php

declare(strict_types=1);

namespace App\Service;

use App\Bundle\Html;
use App\Controller\LogInUserController;
use App\Core\{Config, Email, Token};
use App\Repository\UserRepository;
use App\Validator\LogInUserValidator;

class LogInUserService
{
    protected LogInUserController $logInUserController;
    protected Config $config;
    protected Email $mail;
    protected Html $html;
    protected Token $csrfToken;
    protected LogInUserValidator $logInUserValidator;

    public function __construct(
        LogInUserController $logInUserController,
        Config $config,
        Email $mail,
        Html $html,
        Token $csrfToken,
        LogInUserValidator $logInUserValidator
    ) {
        $this->logInUserController = $logInUserController;
        $this->config = $config;
        $this->mail = $mail;
        $this->html = $html;
        $this->csrfToken = $csrfToken;
        $this->logInUserValidator = $logInUserValidator;
    }

    public function logInUserAction(
        string $login,
        string $password,
        bool $remember,
        bool $submit,
        string $token
    ): array {
        $rm = $this->logInUserController->getManager();
        $ur = $rm->getRepository(UserRepository::class);

        if ($submit) {
            $this->logInUserValidator->validate($login, $password, $token);

            if ($this->logInUserValidator->isValid()) {
                $loginUserData = $ur->getLoginUserData($login);

                $passwordVerified = password_verify(
                    $password,
                    $loginUserData['user_password'] ?? ''
                );

                if ($passwordVerified) {
                    if (!$loginUserData['user_active']) {
                        $activationEmailSent = $this->sendActivationEmail(
                            $loginUserData['user_email'],
                            $loginUserData['user_login'],
                            $loginUserData['user_key']
                        );

                        return array(
                            'content' => 'log-in-user/'
                                . 'account-not-active-info.php',
                            'activeMenu' => 'log-in-user',
                            'title' => 'Information',
                            'activationEmailSent' => $activationEmailSent
                        );
                    }

                    $ur->setUserLoged(
                        $loginUserData['user_id'],
                        $this->config->getRemoteAddress(),
                        $this->config->getDateTimeNow()
                    );

                    $_SESSION['id'] = $loginUserData['user_id'];
                    $_SESSION['admin'] = $loginUserData['user_admin'];
                    $_SESSION['user'] = $loginUserData['user_login'];

                    if ($remember) {
                        setcookie(
                            'cookie_login',
                            $loginUserData['user_login'] . ';'
                                . $loginUserData['user_password'],
                            [
                                'expires' => time() + 365 * 24 * 60 * 60,
                                'path' => '/',
                                'domain' => $this->config->getServerName(),
                                'secure' => (
                                    $this->config->getServerPort() === 443
                                ) ? true : false,
                                'httponly' => true,
                                'samesite' => 'Strict'
                            ]
                        );
                    } else {
                        setcookie(
                            'cookie_login',
                            '',
                            0,
                            '/',
                            $this->config->getServerName()
                        );
                    }

                    return $this->logInUserController
                        ->redirectToRoute('main_page');
                } else {
                    $this->logInUserValidator->addError(
                        'The account with the given login and password '
                            . 'does not exist.'
                    );
                }
            }
        }

        return array(
            'content' => 'log-in-user/log-in-user.php',
            'activeMenu' => 'log-in-user',
            'title' => 'User Login',
            'error' => $this->html->prepareError(
                $this->logInUserValidator->getError()
            ),
            'login' => $login,
            'remember' => $remember,
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
