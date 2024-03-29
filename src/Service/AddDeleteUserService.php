<?php

declare(strict_types=1);

namespace App\Service;

use App\Bundle\{Html, Key};
use App\Controller\AddDeleteUserController;
use App\Core\{Config, Email, Token};
use App\Repository\UserRepository;
use App\Validator\AddUserValidator;

class AddDeleteUserService
{
    protected AddDeleteUserController $addDeleteUserController;
    protected Config $config;
    protected Email $mail;
    protected Html $html;
    protected Key $key;
    protected Token $csrfToken;
    protected AddUserValidator $addUserValidator;

    public function __construct(
        AddDeleteUserController $addDeleteUserController,
        Config $config,
        Email $mail,
        Html $html,
        Key $key,
        Token $csrfToken,
        AddUserValidator $addUserValidator
    ) {
        $this->addDeleteUserController = $addDeleteUserController;
        $this->config = $config;
        $this->mail = $mail;
        $this->html = $html;
        $this->key = $key;
        $this->csrfToken = $csrfToken;
        $this->addUserValidator = $addUserValidator;
    }

    public function addDeleteUserAction(
        string $login,
        string $email,
        string $repeatEmail,
        string $password,
        string $repeatPassword,
        bool $admin,
        bool $submit,
        string $token,
        int $level,
        int $delete,
        int $sessionId
    ): array {
        $rm = $this->addDeleteUserController->getManager();
        $ur = $rm->getRepository(UserRepository::class);

        if ($submit) {
            $this->addUserValidator->validate(
                $login,
                $email,
                $repeatEmail,
                $password,
                $repeatPassword,
                $token
            );

            if ($this->addUserValidator->isValid()) {
                $key = $this->key->generateKey();

                $additionUserDataAdded = $ur->addAdditionUserData(
                    $admin,
                    $login,
                    $email,
                    $password,
                    $key,
                    $this->config->getRemoteAddress(),
                    $this->config->getDateTimeNow()
                );

                if ($additionUserDataAdded) {
                    $activationEmailSent = $this->sendActivationEmail(
                        $email,
                        $login,
                        $key
                    );

                    return array(
                        'content' => 'add-delete-user/'
                            . 'account-created-info.php',
                        'activeMenu' => 'add-delete-user',
                        'title' => 'Information',
                        'activationEmailSent' => $activationEmailSent
                    );
                } else {
                    return array(
                        'content' => 'add-delete-user/'
                            . 'account-not-created-info.php',
                        'activeMenu' => 'add-delete-user',
                        'title' => 'Information'
                    );
                }
            }
        }

        if ($delete > 0) {
            if ($delete === $sessionId) {
                return array(
                    'content' => 'add-delete-user/'
                        . 'account-deletion-stopped-info.php',
                    'activeMenu' => 'add-delete-user',
                    'title' => 'Information'
                );
            }

            $deletionUserDataDeleted = $ur->deleteDeletionUserData($delete);

            if ($deletionUserDataDeleted) {
                return array(
                    'content' => 'add-delete-user/'
                        . 'account-deleted-info.php',
                    'activeMenu' => 'add-delete-user',
                    'title' => 'Information'
                );
            } else {
                return array(
                    'content' => 'add-delete-user/'
                        . 'account-not-deleted-info.php',
                    'activeMenu' => 'add-delete-user',
                    'title' => 'Information'
                );
            }
        }

        $userList = $ur->getUserList(
            $level,
            $listLimit = 10
        );
        $userCount = $ur->getUserCount();
        $pageNavigator = $this->html->preparePageNavigator(
            $this->config->getUrl() . '/users,',
            $level,
            $listLimit,
            $userCount,
            3
        );

        return array(
            'content' => 'add-delete-user/add-delete-user.php',
            'activeMenu' => 'add-delete-user',
            'title' => 'User Addition / Deletion',
            'error' => $this->html->prepareError(
                $this->addUserValidator->getError()
            ),
            'login' => $login,
            'email' => $email,
            'admin' => $admin,
            'token' => $this->csrfToken->generateToken(),
            'level' => $level,
            'userList' => $userList,
            'pageNavigator' => $pageNavigator
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
