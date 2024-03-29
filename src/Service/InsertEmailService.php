<?php

declare(strict_types=1);

namespace App\Service;

use App\Bundle\Html;
use App\Controller\InsertEmailController;
use App\Core\{Config, Token};
use App\Repository\{EmailRepository, ListRepository};
use App\Validator\{InsertEmailValidator, SelectEmailListValidator};

class InsertEmailService
{
    protected InsertEmailController $insertEmailController;
    protected Config $config;
    protected Html $html;
    protected Token $csrfToken;
    protected SelectEmailListValidator $selectEmailListValidator;
    protected InsertEmailValidator $insertEmailValidator;

    public function __construct(
        InsertEmailController $insertEmailController,
        Config $config,
        Html $html,
        Token $csrfToken,
        SelectEmailListValidator $selectEmailListValidator,
        InsertEmailValidator $insertEmailValidator
    ) {
        $this->insertEmailController = $insertEmailController;
        $this->config = $config;
        $this->html = $html;
        $this->csrfToken = $csrfToken;
        $this->selectEmailListValidator = $selectEmailListValidator;
        $this->insertEmailValidator = $insertEmailValidator;
    }

    public function insertEmailAction(
        int $list,
        string $name,
        string $email,
        bool $submit,
        bool $submit2,
        string $token
    ): array {
        $rm = $this->insertEmailController->getManager();
        $lr = $rm->getRepository(ListRepository::class);
        $er = $rm->getRepository(EmailRepository::class);

        if ($submit) {
            $this->selectEmailListValidator->validate($list, $token);
        }

        if ($submit2) {
            $this->insertEmailValidator->validate(
                $list,
                $name,
                $email,
                $token
            );

            if ($this->insertEmailValidator->isValid()) {
                $emailDataAdded = $er->addEmailData(
                    $list,
                    $name,
                    $email,
                    $this->config->getRemoteAddress(),
                    $this->config->getDateTimeNow()
                );

                if ($emailDataAdded) {
                    return array(
                        'content' => 'insert-email/email-added-info.php',
                        'activeMenu' => 'insert-email',
                        'title' => 'Information'
                    );
                } else {
                    return array(
                        'content' => 'insert-email/email-not-added-info.php',
                        'activeMenu' => 'insert-email',
                        'title' => 'Information'
                    );
                }
            }
        }

        $listList = $lr->getListList();

        return array(
            'content' => 'insert-email/insert-email.php',
            'activeMenu' => 'insert-email',
            'title' => 'Email Insertion',
            'error' => $this->html->prepareError(
                $this->selectEmailListValidator->getError()
            ),
            'error2' => $this->html->prepareError(
                $this->insertEmailValidator->getError()
            ),
            'list' => $list,
            'name' => $name,
            'email' => $email,
            'token' => $this->csrfToken->generateToken(),
            'listList' => $listList
        );
    }
}
