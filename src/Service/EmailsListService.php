<?php

declare(strict_types=1);

namespace App\Service;

use App\Bundle\Html;
use App\Core\{Config, Manager, Token};
use App\Repository\{EmailRepository, ListRepository};
use App\Validator\{SearchEmailValidator, SelectEmailListValidator};

class EmailsListService
{
    protected Manager $rm;
    protected Config $config;
    protected Html $html;
    protected Token $csrfToken;
    protected SelectEmailListValidator $selectEmailListValidator;
    protected SearchEmailValidator $searchEmailValidator;

    public function __construct(
        Manager $rm,
        Config $config,
        Html $html,
        Token $csrfToken,
        SelectEmailListValidator $selectEmailListValidator,
        SearchEmailValidator $searchEmailValidator
    ) {
        $this->rm = $rm;
        $this->config = $config;
        $this->html = $html;
        $this->csrfToken = $csrfToken;
        $this->selectEmailListValidator = $selectEmailListValidator;
        $this->searchEmailValidator = $searchEmailValidator;
    }

    public function emailsListAction(
        int $list,
        string $email,
        bool $submit,
        bool $submit2,
        string $token,
        int $level,
        int $delete
    ): array {
        $lr = $this->rm->getRepository(ListRepository::class);
        $er = $this->rm->getRepository(EmailRepository::class);

        if ($submit) {
            $this->selectEmailListValidator->validate($list, $token);
        }

        if ($submit2) {
            $this->searchEmailValidator->validate($list, $email, $token);
        }

        if ($delete > 0) {
            $emailDataDeleted = $er->deleteEmailData($delete);

            if ($emailDataDeleted) {
                return array(
                    'content' => 'emails-list/email-deleted-info.php',
                    'activeMenu' => 'emails-list',
                    'title' => 'Information'
                );
            } else {
                return array(
                    'content' => 'emails-list/email-not-deleted-info.php',
                    'activeMenu' => 'emails-list',
                    'title' => 'Information'
                );
            }
        }

        $listList = $lr->getListList();
        $emailList = $er->getEmailList(
            $list,
            $email,
            $level,
            $listLimit = 10
        );
        $emailCount = $er->getEmailCount($list, $email);
        $pageNavigator = $this->html->preparePageNavigator(
            $this->config->getUrl() . '/emails,' . $list . ',',
            $level,
            $listLimit,
            $emailCount,
            3
        );

        $number = $emailCount - ($level - 1) * $listLimit;

        return array(
            'content' => 'emails-list/emails-list.php',
            'activeMenu' => 'emails-list',
            'title' => 'Emails List',
            'error' => $this->html->prepareError(
                $this->selectEmailListValidator->getError()
            ),
            'error2' => $this->html->prepareError(
                $this->searchEmailValidator->getError()
            ),
            'list' => $list,
            'email' => $email,
            'token' => $this->csrfToken->generateToken(),
            'level' => $level,
            'number' => $number,
            'listList' => $listList,
            'emailList' => $emailList,
            'pageNavigator' => $pageNavigator
        );
    }
}
