<?php

declare(strict_types=1);

namespace App\Service;

use App\Bundle\Html;
use App\Controller\WriteTextController;
use App\Core\{Config, Token};
use App\Repository\TextRepository;
use App\Validator\WriteTextValidator;

class WriteTextService
{
    protected WriteTextController $writeTextController;
    protected Config $config;
    protected Html $html;
    protected Token $csrfToken;
    protected WriteTextValidator $writeTextValidator;

    public function __construct(
        WriteTextController $writeTextController,
        Config $config,
        Html $html,
        Token $csrfToken,
        WriteTextValidator $writeTextValidator
    ) {
        $this->writeTextController = $writeTextController;
        $this->config = $config;
        $this->html = $html;
        $this->csrfToken = $csrfToken;
        $this->writeTextValidator = $writeTextValidator;
    }

    public function writeTextAction(
        string $subject,
        string $message,
        bool $submit,
        string $token,
        int $level,
        int $delete
    ): array {
        $rm = $this->writeTextController->getManager();
        $tr = $rm->getRepository(TextRepository::class);

        if ($submit) {
            $this->writeTextValidator->validate($subject, $message, $token);

            if ($this->writeTextValidator->isValid()) {
                $textDataAdded = $tr->addTextData($subject, $message);

                if ($textDataAdded) {
                    return array(
                        'content' => 'write-text/text-saved-info.php',
                        'activeMenu' => 'write-text',
                        'title' => 'Information'
                    );
                } else {
                    return array(
                        'content' => 'write-text/text-not-saved-info.php',
                        'activeMenu' => 'write-text',
                        'title' => 'Information'
                    );
                }
            }
        }

        if ($delete > 0) {
            $textDataDeleted = $tr->deleteTextData($delete);

            if ($textDataDeleted) {
                return array(
                    'content' => 'write-text/text-deleted-info.php',
                    'activeMenu' => 'write-text',
                    'title' => 'Information'
                );
            } else {
                return array(
                    'content' => 'write-text/text-not-deleted-info.php',
                    'activeMenu' => 'write-text',
                    'title' => 'Information'
                );
            }
        }

        $textList = $tr->getTextList(
            $level,
            $listLimit = 10
        );
        $textCount = $tr->getTextCount();
        $pageNavigator = $this->html->preparePageNavigator(
            $this->config->getUrl() . '/write,',
            $level,
            $listLimit,
            $textCount,
            3
        );

        return array(
            'content' => 'write-text/write-text.php',
            'activeMenu' => 'write-text',
            'title' => 'Text Writing',
            'error' => $this->html->prepareError(
                $this->writeTextValidator->getError()
            ),
            'subject' => $subject,
            'message' => $message,
            'token' => $this->csrfToken->generateToken(),
            'level' => $level,
            'textList' => $textList,
            'pageNavigator' => $pageNavigator
        );
    }
}
