<?php

declare(strict_types=1);

namespace App\Service;

use App\Bundle\Html;
use App\Controller\ExportEmailsController;
use App\Core\{Cache, Token};
use App\Repository\{EmailRepository, ListRepository};
use App\Validator\{ExportEmailsValidator, SelectEmailListValidator};

class ExportEmailsService
{
    protected ExportEmailsController $exportEmailsController;
    protected Cache $cache;
    protected Html $html;
    protected Token $csrfToken;
    protected SelectEmailListValidator $selectEmailListValidator;
    protected ExportEmailsValidator $exportEmailsValidator;

    public function __construct(
        ExportEmailsController $exportEmailsController,
        Cache $cache,
        Html $html,
        Token $csrfToken,
        SelectEmailListValidator $selectEmailListValidator,
        ExportEmailsValidator $exportEmailsValidator
    ) {
        $this->exportEmailsController = $exportEmailsController;
        $this->cache = $cache;
        $this->html = $html;
        $this->csrfToken = $csrfToken;
        $this->selectEmailListValidator = $selectEmailListValidator;
        $this->exportEmailsValidator = $exportEmailsValidator;
    }

    public function exportEmailsAction(
        int $list,
        bool $submit,
        bool $submit2,
        string $token
    ): array {
        $rm = $this->exportEmailsController->getManager();
        $lr = $rm->getRepository(ListRepository::class);
        $er = $rm->getRepository(EmailRepository::class);

        if ($submit) {
            $this->selectEmailListValidator->validate($list, $token);
        }

        if ($submit2) {
            $this->exportEmailsValidator->validate($list, $token);

            if ($this->exportEmailsValidator->isValid()) {
                $exportListData = $lr->getExportListData($list);
                $exportEmailList = $er->getExportEmailList($list);

                $file = EXPORT_DIR . ($exportListData['list_name'] ?? $list)
                    . '.json';

                $this->cache->cacheFile($file, json_encode($exportEmailList));

                if (file_exists($file)) {
                    header('Content-Description: File Transfer');
                    header('Content-Type: application/octet-stream');
                    header(
                        'Content-Disposition: attachment; filename="'
                            . basename($file) . '"'
                    );
                    header('Expires: 0');
                    header('Cache-Control: must-revalidate');
                    header('Pragma: public');
                    header('Content-Length: ' . filesize($file));

                    readfile($file);
                    unlink($file);

                    exit;
                }
            }
        }

        $listList = $lr->getListList();

        return array(
            'content' => 'export-emails/export-emails.php',
            'activeMenu' => 'export-emails',
            'title' => 'Emails Export',
            'error' => $this->html->prepareError(
                $this->selectEmailListValidator->getError()
            ),
            'error2' => $this->html->prepareError(
                $this->exportEmailsValidator->getError()
            ),
            'list' => $list,
            'token' => $this->csrfToken->generateToken(),
            'listList' => $listList
        );
    }
}
