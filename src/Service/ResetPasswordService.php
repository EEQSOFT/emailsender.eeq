<?php

declare(strict_types=1);

namespace App\Service;

use App\Bundle\Html;
use App\Controller\ResetPasswordController;
use App\Core\{Config, Email, Token};
use App\Repository\UserRepository;
use App\Validator\ResetPasswordValidator;

class ResetPasswordService
{
    protected ResetPasswordController $resetPasswordController;
    protected Config $config;
    protected Email $mail;
    protected Html $html;
    protected Token $csrfToken;
    protected ResetPasswordValidator $resetPasswordValidator;

    public function __construct(
        ResetPasswordController $resetPasswordController,
        Config $config,
        Email $mail,
        Html $html,
        Token $csrfToken,
        ResetPasswordValidator $resetPasswordValidator
    ) {
        $this->resetPasswordController = $resetPasswordController;
        $this->config = $config;
        $this->mail = $mail;
        $this->html = $html;
        $this->csrfToken = $csrfToken;
        $this->resetPasswordValidator = $resetPasswordValidator;
    }

    public function resetPasswordAction(
        string $login,
        bool $submit,
        string $token
    ): array {
        $rm = $this->resetPasswordController->getManager();
        $ur = $rm->getRepository(UserRepository::class);

        if ($submit) {
            $this->resetPasswordValidator->validate($login, $token);

            if ($this->resetPasswordValidator->isValid()) {
                $resetUserData = $ur->getResetUserData($login);

                if (!empty($resetUserData)) {
                    if (!$resetUserData['user_active']) {
                        $activationEmailSent = $this->sendActivationEmail(
                            $resetUserData['user_email'],
                            $resetUserData['user_login'],
                            $resetUserData['user_key']
                        );

                        return array(
                            'content' => 'reset-password/'
                                . 'account-not-active-info.php',
                            'activeMenu' => 'reset-password',
                            'title' => 'Information',
                            'activationEmailSent' => $activationEmailSent
                        );
                    }

                    $passwordChangeEmailSent = $this->sendPasswordChangeEmail(
                        $resetUserData['user_email'],
                        $resetUserData['user_login'],
                        $resetUserData['user_key']
                    );

                    return array(
                        'content' => 'reset-password/'
                            . 'more-instructions-info.php',
                        'activeMenu' => 'reset-password',
                        'title' => 'Information',
                        'passwordChangeEmailSent' => $passwordChangeEmailSent
                    );
                } else {
                    $this->resetPasswordValidator->addError(
                        'The account with the given login does not exist.'
                    );
                }
            }
        }

        return array(
            'content' => 'reset-password/reset-password.php',
            'activeMenu' => 'reset-password',
            'title' => 'Password Reset',
            'error' => $this->html->prepareError(
                $this->resetPasswordValidator->getError()
            ),
            'login' => $login,
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

    private function sendPasswordChangeEmail(
        string $email,
        string $login,
        string $key
    ): bool {
        return $this->mail->sendEmail(
            $this->config->getServerName(),
            $this->config->getAdminEmail(),
            $email,
            'The ' . $login . ' account password change in the '
                . $this->config->getServerDomain() . ' service',
            'To change the account password, open the url below in a browser '
                . 'window.' . "\n\n" . $this->config->getUrl()
                . '/change-password,' . $login . ',' . $key . "\n\n"
                . '--' . "\n" . $this->config->getAdminEmail()
        );
    }
}
