<?php

declare(strict_types=1);

namespace App\Service;

use App\Bundle\{Html, Key};
use App\Controller\ChangePasswordController;
use App\Core\{Config, Email, Token};
use App\Repository\UserRepository;
use App\Validator\ChangePasswordValidator;

class ChangePasswordService
{
    protected ChangePasswordController $changePasswordController;
    protected Config $config;
    protected Email $mail;
    protected Html $html;
    protected Key $key;
    protected Token $csrfToken;
    protected ChangePasswordValidator $changePasswordValidator;

    public function __construct(
        ChangePasswordController $changePasswordController,
        Config $config,
        Email $mail,
        Html $html,
        Key $key,
        Token $csrfToken,
        ChangePasswordValidator $changePasswordValidator
    ) {
        $this->changePasswordController = $changePasswordController;
        $this->config = $config;
        $this->mail = $mail;
        $this->html = $html;
        $this->key = $key;
        $this->csrfToken = $csrfToken;
        $this->changePasswordValidator = $changePasswordValidator;
    }

    public function changePasswordAction(
        string $newPassword,
        string $repeatPassword,
        bool $submit,
        string $token,
        string $user,
        string $code
    ): array {
        $rm = $this->changePasswordController->getManager();
        $ur = $rm->getRepository(UserRepository::class);

        if ($user !== '' && $code !== '') {
            $changeUserData = $ur->getChangeUserData($user);

            if ($code !== ($changeUserData['user_key'] ?? '')) {
                return array(
                    'content' => 'change-password/code-not-valid-info.php',
                    'activeMenu' => 'change-password',
                    'title' => 'Information'
                );
            }

            if (!$changeUserData['user_active']) {
                $activationEmailSent = $this->sendActivationEmail(
                    $changeUserData['user_email'],
                    $changeUserData['user_login'],
                    $changeUserData['user_key']
                );

                return array(
                    'content' => 'change-password/account-not-active-info.php',
                    'activeMenu' => 'change-password',
                    'title' => 'Information',
                    'activationEmailSent' => $activationEmailSent
                );
            }

            if ($submit) {
                $this->changePasswordValidator->validate(
                    $newPassword,
                    $repeatPassword,
                    $token
                );

                if ($this->changePasswordValidator->isValid()) {
                    $key = $this->key->generateKey();

                    $changeUserDataSet = $ur->setChangeUserData(
                        $changeUserData['user_id'],
                        $newPassword,
                        $key,
                        $this->config->getRemoteAddress(),
                        $this->config->getDateTimeNow()
                    );

                    if ($changeUserDataSet) {
                        setcookie(
                            'cookie_login',
                            '',
                            0,
                            '/',
                            $this->config->getServerName()
                        );

                        session_destroy();

                        return array(
                            'content' => 'change-password/'
                                . 'password-changed-info.php',
                            'activeMenu' => 'change-password',
                            'title' => 'Information'
                        );
                    } else {
                        return array(
                            'content' => 'change-password/'
                                . 'password-not-changed-info.php',
                            'activeMenu' => 'change-password',
                            'title' => 'Information'
                        );
                    }
                }
            }
        }

        return array(
            'content' => 'change-password/change-password.php',
            'activeMenu' => 'change-password',
            'title' => 'Password Change',
            'error' => $this->html->prepareError(
                $this->changePasswordValidator->getError()
            ),
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
