<?php

declare(strict_types=1);

namespace App\Controller;

use App\Bundle\Html;
use App\Core\{Config, Controller, Token};
use App\Service\WriteTextService;
use App\Validator\WriteTextValidator;

class WriteTextController extends Controller
{
    public function writeTextAction(array $request): array
    {
        $config = new Config();
        $html = new Html();
        $csrfToken = new Token();
        $writeTextValidator = new WriteTextValidator($csrfToken);

        $writeTextService = new WriteTextService(
            $this,
            $config,
            $html,
            $csrfToken,
            $writeTextValidator
        );

        $array = $writeTextService->writeTextAction(
            (string) ($request['subject'] ?? ''),
            (string) ($request['message'] ?? ''),
            (bool) ($request['submit'] ?? false),
            (string) ($request['token'] ?? ''),
            (int) ($request['level'] ?? 1),
            (int) ($request['delete'] ?? 0)
        );

        return $array;
    }
}
