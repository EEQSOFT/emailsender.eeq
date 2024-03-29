<?php

declare(strict_types=1);

namespace App\Controller;

use App\Bundle\{Cron, Html};
use App\Core\{Controller, Token};
use App\Service\SendEmailsService;
use App\Validator\{
    SelectEmailListValidator,
    SelectTextValidator,
    SendEmailsValidator
};

class SendEmailsController extends Controller
{
    public function sendEmailsAction(array $request): array
    {
        $cron = new Cron();
        $html = new Html();
        $csrfToken = new Token();
        $selectEmailListValidator = new SelectEmailListValidator($csrfToken);
        $selectTextValidator = new SelectTextValidator($csrfToken);
        $sendEmailsValidator = new SendEmailsValidator($csrfToken);

        $sendEmailsService = new SendEmailsService(
            $this,
            $cron,
            $html,
            $csrfToken,
            $selectEmailListValidator,
            $selectTextValidator,
            $sendEmailsValidator
        );

        $array = $sendEmailsService->sendEmailsAction(
            (int) ($request['list'] ?? 0),
            (int) ($request['text'] ?? 0),
            (bool) ($request['submit'] ?? false),
            (bool) ($request['submit2'] ?? false),
            (bool) ($request['submit3'] ?? false),
            (bool) ($request['submit4'] ?? false),
            (string) ($request['token'] ?? '')
        );

        return $array;
    }
}
