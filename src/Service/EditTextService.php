<?php

declare(strict_types=1);

namespace App\Service;

use App\Bundle\Html;
use App\Controller\EditTextController;
use App\Core\Token;
use App\Repository\TextRepository;
use App\Validator\EditTextValidator;

class EditTextService
{
    protected EditTextController $editTextController;
    protected Html $html;
    protected Token $csrfToken;
    protected EditTextValidator $editTextValidator;

    public function __construct(
        EditTextController $editTextController,
        Html $html,
        Token $csrfToken,
        EditTextValidator $editTextValidator
    ) {
        $this->editTextController = $editTextController;
        $this->html = $html;
        $this->csrfToken = $csrfToken;
        $this->editTextValidator = $editTextValidator;
    }

    public function editTextAction(
        string $subject,
        string $message,
        bool $submit,
        string $token,
        int $edit
    ): array {
        $rm = $this->editTextController->getManager();
        $tr = $rm->getRepository(TextRepository::class);

        if ($submit) {
            $this->editTextValidator->validate($subject, $message, $token);

            if ($this->editTextValidator->isValid()) {
                $textDataSet = $tr->setTextData($edit, $subject, $message);

                if ($textDataSet) {
                    return array(
                        'content' => 'edit-text/text-saved-info.php',
                        'activeMenu' => 'write-text',
                        'title' => 'Information'
                    );
                } else {
                    return array(
                        'content' => 'edit-text/text-not-saved-info.php',
                        'activeMenu' => 'write-text',
                        'title' => 'Information'
                    );
                }
            }
        } else {
            $textData = $tr->getTextData($edit);
        }

        return array(
            'content' => 'edit-text/edit-text.php',
            'activeMenu' => 'write-text',
            'title' => 'Text Editing',
            'error' => $this->html->prepareError(
                $this->editTextValidator->getError()
            ),
            'subject' => $textData['text_subject'] ?? $subject,
            'message' => $textData['text_message'] ?? $message,
            'token' => $this->csrfToken->generateToken()
        );
    }
}
