<?php

declare(strict_types=1);

namespace App\Controller;

use App\Bundle\{Html, Key};
use App\Core\{Config, Controller, Email, Token};
use App\Service\AddDeleteUserService;
use App\Validator\AddUserValidator;

class AddDeleteUserController extends Controller
{
    public function addDeleteUserAction(array $request, array $session): array
    {
        $config = new Config();
        $mail = new Email();
        $html = new Html();
        $key = new Key();
        $csrfToken = new Token();
        $addUserValidator = new AddUserValidator(
            $csrfToken,
            $this->getManager()
        );

        $addDeleteUserService = new AddDeleteUserService(
            $this,
            $config,
            $mail,
            $html,
            $key,
            $csrfToken,
            $addUserValidator
        );

        $array = $addDeleteUserService->addDeleteUserAction(
            (string) ($request['login'] ?? ''),
            (string) ($request['email'] ?? ''),
            (string) ($request['repeat_email'] ?? ''),
            (string) ($request['password'] ?? ''),
            (string) ($request['repeat_password'] ?? ''),
            (bool) ($request['admin'] ?? false),
            (bool) ($request['submit'] ?? false),
            (string) ($request['token'] ?? ''),
            (int) ($request['level'] ?? 1),
            (int) ($request['delete'] ?? 0),
            (int) ($session['id'] ?? 0)
        );

        return $array;
    }
}
