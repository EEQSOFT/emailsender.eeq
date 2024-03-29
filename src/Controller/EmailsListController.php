<?php

declare(strict_types=1);

namespace App\Controller;

use App\Bundle\Html;
use App\Core\{Config, Controller, Token};
use App\Service\EmailsListService;
use App\Validator\{SearchEmailValidator, SelectEmailListValidator};

class EmailsListController extends Controller
{
    public function emailsListAction(array $request): array
    {
        $config = new Config();
        $html = new Html();
        $csrfToken = new Token();
        $selectEmailListValidator = new SelectEmailListValidator($csrfToken);
        $searchEmailValidator = new SearchEmailValidator($csrfToken);

        $emailsListService = new EmailsListService(
            $this,
            $config,
            $html,
            $csrfToken,
            $selectEmailListValidator,
            $searchEmailValidator
        );

        $array = $emailsListService->emailsListAction(
            (int) ($request['list'] ?? 0),
            (string) ($request['email'] ?? ''),
            (bool) ($request['submit'] ?? false),
            (bool) ($request['submit2'] ?? false),
            (string) ($request['token'] ?? ''),
            (int) ($request['level'] ?? 1),
            (int) ($request['delete'] ?? 0)
        );

        return $array;
    }
}
