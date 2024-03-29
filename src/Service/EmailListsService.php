<?php

declare(strict_types=1);

namespace App\Service;

use App\Bundle\Html;
use App\Controller\EmailListsController;
use App\Core\Token;
use App\Repository\ListRepository;
use App\Validator\{AddEmailListValidator, DeleteEmailListValidator};

class EmailListsService
{
    protected EmailListsController $emailListsController;
    protected Html $html;
    protected Token $csrfToken;
    protected AddEmailListValidator $addEmailListValidator;
    protected DeleteEmailListValidator $deleteEmailListValidator;

    public function __construct(
        EmailListsController $emailListsController,
        Html $html,
        Token $csrfToken,
        AddEmailListValidator $addEmailListValidator,
        DeleteEmailListValidator $deleteEmailListValidator
    ) {
        $this->emailListsController = $emailListsController;
        $this->html = $html;
        $this->csrfToken = $csrfToken;
        $this->addEmailListValidator = $addEmailListValidator;
        $this->deleteEmailListValidator = $deleteEmailListValidator;
    }

    public function emailListsAction(
        string $name,
        int $list,
        bool $submit,
        bool $submit2,
        string $token
    ): array {
        $rm = $this->emailListsController->getManager();
        $lr = $rm->getRepository(ListRepository::class);

        if ($submit) {
            $this->addEmailListValidator->validate($name, $token);

            if ($this->addEmailListValidator->isValid()) {
                $listDataAdded = $lr->addListData($name);

                if ($listDataAdded) {
                    return array(
                        'content' => 'email-lists/list-added-info.php',
                        'activeMenu' => 'email-lists',
                        'title' => 'Information'
                    );
                } else {
                    return array(
                        'content' => 'email-lists/list-not-added-info.php',
                        'activeMenu' => 'email-lists',
                        'title' => 'Information'
                    );
                }
            }
        }

        if ($submit2) {
            $this->deleteEmailListValidator->validate($list, $token);

            if ($this->deleteEmailListValidator->isValid()) {
                $emailListDataDeleted = $lr->deleteEmailListData($list);

                if ($emailListDataDeleted) {
                    return array(
                        'content' => 'email-lists/list-deleted-info.php',
                        'activeMenu' => 'email-lists',
                        'title' => 'Information'
                    );
                } else {
                    return array(
                        'content' => 'email-lists/list-not-deleted-info.php',
                        'activeMenu' => 'email-lists',
                        'title' => 'Information'
                    );
                }
            }
        }

        $listList = $lr->getListList();

        return array(
            'content' => 'email-lists/email-lists.php',
            'activeMenu' => 'email-lists',
            'title' => 'Email Lists',
            'error' => $this->html->prepareError(
                $this->addEmailListValidator->getError()
            ),
            'error2' => $this->html->prepareError(
                $this->deleteEmailListValidator->getError()
            ),
            'name' => $name,
            'list' => $list,
            'token' => $this->csrfToken->generateToken(),
            'listList' => $listList
        );
    }
}
