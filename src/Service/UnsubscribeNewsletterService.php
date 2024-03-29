<?php

declare(strict_types=1);

namespace App\Service;

use App\Controller\UnsubscribeNewsletterController;
use App\Repository\EmailRepository;

class UnsubscribeNewsletterService
{
    protected UnsubscribeNewsletterController $unsubscribeNewsletterController;

    public function __construct(
        UnsubscribeNewsletterController $unsubscribeNewsletterController
    ) {
        $this->unsubscribeNewsletterController =
            $unsubscribeNewsletterController;
    }

    public function unsubscribeNewsletterAction(
        int $email,
        string $code
    ): array {
        $rm = $this->unsubscribeNewsletterController->getManager();
        $er = $rm->getRepository(EmailRepository::class);

        if ($email > 0 && $code !== '') {
            $unsubscribingEmailData = $er->getUnsubscribingEmailData($email);

            if (($unsubscribingEmailData['email_id'] ?? 0) > 0) {
                $emailKey = md5(
                    $unsubscribingEmailData['email_id']
                    . $unsubscribingEmailData['email_name']
                    . $unsubscribingEmailData['email_email']
                    . $unsubscribingEmailData['email_ip_added']
                    . $unsubscribingEmailData['email_date_added']
                );
            }

            if ($code !== ($emailKey ?? '')) {
                return array(
                    'layout' => 'layout/newsletter/main.php',
                    'content' => 'unsubscribe-newsletter/'
                        . 'code-not-valid-info.php',
                    'activeMenu' => 'unsubscribe-newsletter',
                    'title' => 'Information'
                );
            }

            $emailDataDeleted = $er->deleteEmailData($email);

            return array(
                'layout' => 'layout/newsletter/main.php',
                'content' => 'unsubscribe-newsletter/'
                    . 'newsletter-unsubscribing-info.php',
                'activeMenu' => 'unsubscribe-newsletter',
                'title' => 'Information',
                'emailDataDeleted' => $emailDataDeleted
            );
        }

        return array(
            'layout' => 'layout/newsletter/main.php',
            'content' => 'unsubscribe-newsletter/unsubscribe-newsletter.php',
            'activeMenu' => 'unsubscribe-newsletter',
            'title' => 'Newsletter Unsubscribing'
        );
    }
}
