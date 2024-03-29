<?php

declare(strict_types=1);

namespace App\Service;

use App\Bundle\Html;
use App\Controller\ImportEmailsController;
use App\Core\Token;
use App\Repository\{EmailRepository, ListRepository};
use App\Validator\{ImportEmailsValidator, SelectEmailListValidator};

class ImportEmailsService
{
    protected ImportEmailsController $importEmailsController;
    protected Html $html;
    protected Token $csrfToken;
    protected SelectEmailListValidator $selectEmailListValidator;
    protected ImportEmailsValidator $importEmailsValidator;

    public function __construct(
        ImportEmailsController $importEmailsController,
        Html $html,
        Token $csrfToken,
        SelectEmailListValidator $selectEmailListValidator,
        ImportEmailsValidator $importEmailsValidator
    ) {
        $this->importEmailsController = $importEmailsController;
        $this->html = $html;
        $this->csrfToken = $csrfToken;
        $this->selectEmailListValidator = $selectEmailListValidator;
        $this->importEmailsValidator = $importEmailsValidator;
    }

    public function importEmailsAction(
        int $list,
        bool $submit,
        bool $submit2,
        string $token,
        string $originalFile,
        string $temporaryFile,
        int $fileError
    ): array {
        $rm = $this->importEmailsController->getManager();
        $lr = $rm->getRepository(ListRepository::class);
        $er = $rm->getRepository(EmailRepository::class);

        if ($submit) {
            $this->selectEmailListValidator->validate($list, $token);
        }

        if ($submit2) {
            $this->importEmailsValidator->validate(
                $list,
                $originalFile,
                $temporaryFile,
                $fileError,
                $token
            );

            if ($this->importEmailsValidator->isValid()) {
                $file = IMPORT_DIR . basename($originalFile);

                move_uploaded_file($temporaryFile, $file);

                $importEmailList = json_decode(
                    file_get_contents($file),
                    true
                ) ?? [];

                unlink($file);

                if (!empty($importEmailList)) {
                    foreach ($importEmailList as $key => $value) {
                        $value['email_name'] ??= '';
                        $value['email_email'] ??= '';
                        $value['email_ip_added'] ??= '';
                        $value['email_date_added'] ??= '';

                        $value['email_date_added'] = (
                            $value['email_date_added'] < '1970-01-01 00:00:00'
                        ) ? '1970-01-01 00:00:00' : $value['email_date_added'];

                        if (
                            $value['email_name'] !== ''
                            && preg_match(
                                '/^([0-9A-Za-z._-]+)@([0-9A-Za-z-]+\.)+'
                                    . '([0-9A-Za-z]{1,63})$/',
                                $value['email_email']
                            ) === 1
                            && !$er->isEmailEmail($list, $value['email_email'])
                        ) {
                            $er->addEmailData(
                                $list,
                                $value['email_name'],
                                $value['email_email'],
                                $value['email_ip_added'],
                                $value['email_date_added']
                            );
                        }
                    }

                    return array(
                        'content' => 'import-emails/'
                            . 'emails-imported-info.php',
                        'activeMenu' => 'import-emails',
                        'title' => 'Information'
                    );
                } else {
                    return array(
                        'content' => 'import-emails/'
                            . 'emails-not-imported-info.php',
                        'activeMenu' => 'import-emails',
                        'title' => 'Information'
                    );
                }
            }
        }

        $listList = $lr->getListList();

        return array(
            'content' => 'import-emails/import-emails.php',
            'activeMenu' => 'import-emails',
            'title' => 'Emails Import',
            'error' => $this->html->prepareError(
                $this->selectEmailListValidator->getError()
            ),
            'error2' => $this->html->prepareError(
                $this->importEmailsValidator->getError()
            ),
            'list' => $list,
            'token' => $this->csrfToken->generateToken(),
            'listList' => $listList
        );
    }
}
