<?php

declare(strict_types=1);

namespace App\Controller;

use App\Bundle\{Html, Key};
use App\Core\{Cache, Config, Controller, Email, Token};
use App\Repository\OptionRepository;
use App\Service\RegisterUserService;
use App\Validator\RegisterUserValidator;

class RegisterUserController extends Controller
{
    public function registerUserAction(
        array $request,
        array $session,
        array $options
    ): array {
        $config = new Config();
        $cache = new Cache();
        $mail = new Email();
        $html = new Html();
        $key = new Key();
        $csrfToken = new Token();
        $registerUserValidator = new RegisterUserValidator(
            $csrfToken,
            $this->getManager()
        );

        $rm = $this->getManager();
        $or = $rm->getRepository(OptionRepository::class);

        $optionData = $or->getOptionData();

        if (
            $options['registered']
            || ($optionData['option_registered'] ?? false)
        ) {
            return $this->redirectToRoute('login_page');
        }

        $registerUserService = new RegisterUserService(
            $this,
            $config,
            $cache,
            $mail,
            $html,
            $key,
            $csrfToken,
            $registerUserValidator
        );

        $array = $registerUserService->registerUserAction(
            (string) ($request['login'] ?? ''),
            (string) ($request['email'] ?? ''),
            (string) ($request['repeat_email'] ?? ''),
            (string) ($request['password'] ?? ''),
            (string) ($request['repeat_password'] ?? ''),
            (bool) ($request['submit'] ?? false),
            (string) ($request['token'] ?? '')
        );

        return $array;
    }
}
