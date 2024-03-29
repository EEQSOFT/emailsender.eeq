<?php

declare(strict_types=1);

namespace App\Controller;

use App\Bundle\Html;
use App\Core\{Cache, Controller, Token};
use App\Service\ExportEmailsService;
use App\Validator\{ExportEmailsValidator, SelectEmailListValidator};

class ExportEmailsController extends Controller
{
    public function exportEmailsAction(array $request): array
    {
        $cache = new Cache();
        $html = new Html();
        $csrfToken = new Token();
        $selectEmailListValidator = new SelectEmailListValidator($csrfToken);
        $exportEmailsValidator = new ExportEmailsValidator($csrfToken);

        $exportEmailsService = new ExportEmailsService(
            $this,
            $cache,
            $html,
            $csrfToken,
            $selectEmailListValidator,
            $exportEmailsValidator
        );

        $array = $exportEmailsService->exportEmailsAction(
            (int) ($request['list'] ?? 0),
            (bool) ($request['submit'] ?? false),
            (bool) ($request['submit2'] ?? false),
            (string) ($request['token'] ?? '')
        );

        return $array;
    }
}
