<?php

declare(strict_types=1);

namespace App\Controller;

use App\Bundle\Html;
use App\Core\{Controller, Token};
use App\Service\ImportEmailsService;
use App\Validator\{ImportEmailsValidator, SelectEmailListValidator};

class ImportEmailsController extends Controller
{
    public function importEmailsAction(
        array $request,
        array $session,
        array $options,
        array $files
    ): array {
        $html = new Html();
        $csrfToken = new Token();
        $selectEmailListValidator = new SelectEmailListValidator($csrfToken);
        $importEmailsValidator = new ImportEmailsValidator($csrfToken);

        $importEmailsService = new ImportEmailsService(
            $this,
            $html,
            $csrfToken,
            $selectEmailListValidator,
            $importEmailsValidator
        );

        $array = $importEmailsService->importEmailsAction(
            (int) ($request['list'] ?? 0),
            (bool) ($request['submit'] ?? false),
            (bool) ($request['submit2'] ?? false),
            (string) ($request['token'] ?? ''),
            (string) ($files['file']['name'] ?? ''),
            (string) ($files['file']['tmp_name'] ?? ''),
            (int) ($files['file']['error'] ?? 0)
        );

        return $array;
    }
}
