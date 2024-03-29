<?php

declare(strict_types=1);

namespace App\Controller;

use App\Bundle\Html;
use App\Core\{Controller, Token};
use App\Service\EditTextService;
use App\Validator\EditTextValidator;

class EditTextController extends Controller
{
    public function editTextAction(array $request): array
    {
        $html = new Html();
        $csrfToken = new Token();
        $editTextValidator = new EditTextValidator($csrfToken);

        $editTextService = new EditTextService(
            $this,
            $html,
            $csrfToken,
            $editTextValidator
        );

        $array = $editTextService->editTextAction(
            (string) ($request['subject'] ?? ''),
            (string) ($request['message'] ?? ''),
            (bool) ($request['submit'] ?? false),
            (string) ($request['token'] ?? ''),
            (int) ($request['edit'] ?? 0)
        );

        return $array;
    }
}
