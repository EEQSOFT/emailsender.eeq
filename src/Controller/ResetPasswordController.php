<?php

declare(strict_types=1);

namespace App\Controller;

use App\Bundle\Html;
use App\Core\{Config, Controller, Email, Token};
use App\Service\ResetPasswordService;
use App\Validator\ResetPasswordValidator;

class ResetPasswordController extends Controller
{
    public function resetPasswordAction(array $request): array
    {
        $config = new Config();
        $mail = new Email();
        $html = new Html();
        $csrfToken = new Token();
        $resetPasswordValidator = new ResetPasswordValidator($csrfToken);

        $resetPasswordService = new ResetPasswordService(
            $this,
            $config,
            $mail,
            $html,
            $csrfToken,
            $resetPasswordValidator
        );

        $array = $resetPasswordService->resetPasswordAction(
            (string) ($request['login'] ?? ''),
            (bool) ($request['submit'] ?? false),
            (string) ($request['token'] ?? '')
        );

        return $array;
    }
}
