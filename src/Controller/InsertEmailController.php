<?php

declare(strict_types=1);

namespace App\Controller;

use App\Bundle\Html;
use App\Core\{Config, Controller, Token};
use App\Service\InsertEmailService;
use App\Validator\{InsertEmailValidator, SelectEmailListValidator};

class InsertEmailController extends Controller
{
    public function insertEmailAction(array $request): array
    {
        $config = new Config();
        $html = new Html();
        $csrfToken = new Token();
        $selectEmailListValidator = new SelectEmailListValidator($csrfToken);
        $insertEmailValidator = new InsertEmailValidator(
            $csrfToken,
            $this->getManager()
        );

        $insertEmailService = new InsertEmailService(
            $this,
            $config,
            $html,
            $csrfToken,
            $selectEmailListValidator,
            $insertEmailValidator
        );

        $array = $insertEmailService->insertEmailAction(
            (int) ($request['list'] ?? 0),
            (string) ($request['name'] ?? ''),
            (string) ($request['email'] ?? ''),
            (bool) ($request['submit'] ?? false),
            (bool) ($request['submit2'] ?? false),
            (string) ($request['token'] ?? '')
        );

        return $array;
    }
}
