<?php

declare(strict_types=1);

namespace App\Service;

use App\Bundle\{Cron, Html};
use App\Controller\SendEmailsController;
use App\Core\Token;
use App\Repository\{
    EmailRepository,
    ListRepository,
    SendRepository,
    TextRepository
};
use App\Validator\{
    SelectEmailListValidator,
    SelectTextValidator,
    SendEmailsValidator
};

class SendEmailsService
{
    protected SendEmailsController $sendEmailsController;
    protected Cron $cron;
    protected Html $html;
    protected Token $csrfToken;
    protected SelectEmailListValidator $selectEmailListValidator;
    protected SelectTextValidator $selectTextValidator;
    protected SendEmailsValidator $sendEmailsValidator;

    public function __construct(
        SendEmailsController $sendEmailsController,
        Cron $cron,
        Html $html,
        Token $csrfToken,
        SelectEmailListValidator $selectEmailListValidator,
        SelectTextValidator $selectTextValidator,
        SendEmailsValidator $sendEmailsValidator
    ) {
        $this->sendEmailsController = $sendEmailsController;
        $this->cron = $cron;
        $this->html = $html;
        $this->csrfToken = $csrfToken;
        $this->selectEmailListValidator = $selectEmailListValidator;
        $this->selectTextValidator = $selectTextValidator;
        $this->sendEmailsValidator = $sendEmailsValidator;
    }

    public function sendEmailsAction(
        int $list,
        int $text,
        bool $submit,
        bool $submit2,
        bool $submit3,
        bool $submit4,
        string $token
    ): array {
        $rm = $this->sendEmailsController->getManager();
        $lr = $rm->getRepository(ListRepository::class);
        $er = $rm->getRepository(EmailRepository::class);
        $tr = $rm->getRepository(TextRepository::class);
        $sr = $rm->getRepository(SendRepository::class);

        if ($submit) {
            $this->selectEmailListValidator->validate($list, $token);
        }

        if ($submit2) {
            $this->selectTextValidator->validate($text, $token);
        }

        if ($submit3) {
            $this->sendEmailsValidator->validate($list, $text, $token);

            if ($this->sendEmailsValidator->isValid()) {
                $sendingEmailCount = $er->getSendingEmailCount($list, 0);

                $sr->setSendData($list, 0, $text, $sendingEmailCount);

                $this->cron->appendCronjob('* * * * * php ' . CRONJOB_FILE);
            }
        }

        if ($submit4) {
            $this->sendEmailsValidator->validate($list, $text, $token, false);

            if ($this->sendEmailsValidator->isValid()) {
                $sr->setSendData(0, 0, 0, 0);

                $this->cron->removeCrontab();
            }
        }

        if (file_exists(CRONTAB_FILE)) {
            $sendData = $sr->getSendData();
            $sendCount = $sendData['send_count'] ?? 0;
            $sendingEmailCount = $er->getSendingEmailCount(
                $sendData['list_id'] ?? 0,
                $sendData['email_id'] ?? 0
            );

            if ($sendCount > 0) {
                $progress = 100 - round($sendingEmailCount * 100 / $sendCount);
            }
        }

        $listList = $lr->getListList();
        $textList = $tr->getTextList();

        return array(
            'content' => 'send-emails/send-emails.php',
            'activeMenu' => 'send-emails',
            'title' => 'Emails Sending',
            'error' => $this->html->prepareError(
                $this->selectEmailListValidator->getError()
            ),
            'error2' => $this->html->prepareError(
                $this->selectTextValidator->getError()
            ),
            'error3' => $this->html->prepareError(
                $this->sendEmailsValidator->getError()
            ),
            'list' => $sendData['list_id'] ?? $list,
            'text' => $sendData['text_id'] ?? $text,
            'token' => $this->csrfToken->generateToken(),
            'progress' => $progress ?? 100,
            'listList' => $listList,
            'textList' => $textList
        );
    }
}
