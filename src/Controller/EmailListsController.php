<?php

declare(strict_types=1);

namespace App\Controller;

use App\Bundle\Html;
use App\Core\{Controller, Token};
use App\Service\EmailListsService;
use App\Validator\{AddEmailListValidator, DeleteEmailListValidator};

class EmailListsController extends Controller
{
    public function emailListsAction(array $request): array
    {
        $html = new Html();
        $csrfToken = new Token();
        $addEmailListValidator = new AddEmailListValidator(
            $csrfToken,
            $this->getManager()
        );
        $deleteEmailListValidator = new DeleteEmailListValidator($csrfToken);

        $emailListsService = new EmailListsService(
            $this,
            $html,
            $csrfToken,
            $addEmailListValidator,
            $deleteEmailListValidator
        );

        $array = $emailListsService->emailListsAction(
            (string) ($request['name'] ?? ''),
            (int) ($request['list'] ?? 0),
            (bool) ($request['submit'] ?? false),
            (bool) ($request['submit2'] ?? false),
            (string) ($request['token'] ?? '')
        );

        return $array;
    }
}
