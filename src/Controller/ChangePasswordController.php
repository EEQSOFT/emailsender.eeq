<?php

declare(strict_types=1);

namespace App\Controller;

use App\Bundle\{Html, Key};
use App\Core\{Config, Controller, Email, Token};
use App\Service\ChangePasswordService;
use App\Validator\ChangePasswordValidator;

class ChangePasswordController extends Controller
{
    public function changePasswordAction(array $request): array
    {
        $config = new Config();
        $mail = new Email();
        $html = new Html();
        $key = new Key();
        $csrfToken = new Token();
        $changePasswordValidator = new ChangePasswordValidator($csrfToken);

        $changePasswordService = new ChangePasswordService(
            $this,
            $config,
            $mail,
            $html,
            $key,
            $csrfToken,
            $changePasswordValidator
        );

        $array = $changePasswordService->changePasswordAction(
            (string) ($request['new_password'] ?? ''),
            (string) ($request['repeat_password'] ?? ''),
            (bool) ($request['submit'] ?? false),
            (string) ($request['token'] ?? ''),
            (string) ($request['user'] ?? ''),
            (string) ($request['code'] ?? '')
        );

        return $array;
    }
}
