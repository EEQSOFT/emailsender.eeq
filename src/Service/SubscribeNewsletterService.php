<?php

declare(strict_types=1);

namespace App\Service;

use App\Bundle\Html;
use App\Controller\SubscribeNewsletterController;
use App\Core\{Config, Token};
use App\Repository\{EmailRepository, ListRepository};
use App\Validator\SubscribeNewsletterValidator;

class SubscribeNewsletterService
{
    protected SubscribeNewsletterController $subscribeNewsletterController;
    protected Config $config;
    protected Html $html;
    protected Token $csrfToken;
    protected SubscribeNewsletterValidator $subscribeNewsletterValidator;

    public function __construct(
        SubscribeNewsletterController $subscribeNewsletterController,
        Config $config,
        Html $html,
        Token $csrfToken,
        SubscribeNewsletterValidator $subscribeNewsletterValidator
    ) {
        $this->subscribeNewsletterController = $subscribeNewsletterController;
        $this->config = $config;
        $this->html = $html;
        $this->csrfToken = $csrfToken;
        $this->subscribeNewsletterValidator = $subscribeNewsletterValidator;
    }

    public function subscribeNewsletterAction(
        string $name,
        string $email,
        bool $submit,
        string $token,
        string $newsletter
    ): array {
        $rm = $this->subscribeNewsletterController->getManager();
        $lr = $rm->getRepository(ListRepository::class);
        $er = $rm->getRepository(EmailRepository::class);

        if ($submit) {
            if (!$lr->isListName($newsletter)) {
                $lr->addListData($newsletter);
            }

            $list = $lr->getListId($newsletter);

            $this->subscribeNewsletterValidator->validate(
                $list,
                $name,
                $email,
                $this->config->getRemoteAddress(),
                $token
            );

            if ($this->subscribeNewsletterValidator->isValid()) {
                $emailDataAdded = $er->addEmailData(
                    $list,
                    $name,
                    $email,
                    $this->config->getRemoteAddress(),
                    $this->config->getDateTimeNow()
                );

                if ($emailDataAdded) {
                    return array(
                        'layout' => 'layout/newsletter/main.php',
                        'content' => 'subscribe-newsletter/'
                            . 'newsletter-subscribed-info.php',
                        'activeMenu' => 'subscribe-newsletter',
                        'title' => 'Information'
                    );
                } else {
                    return array(
                        'layout' => 'layout/newsletter/main.php',
                        'content' => 'subscribe-newsletter/'
                            . 'newsletter-not-subscribed-info.php',
                        'activeMenu' => 'subscribe-newsletter',
                        'title' => 'Information'
                    );
                }
            }
        }

        return array(
            'layout' => 'layout/newsletter/main.php',
            'content' => 'subscribe-newsletter/subscribe-newsletter.php',
            'activeMenu' => 'subscribe-newsletter',
            'title' => 'Newsletter Subscribing',
            'error' => $this->html->prepareError(
                $this->subscribeNewsletterValidator->getError()
            ),
            'name' => $name,
            'email' => $email,
            'token' => $this->csrfToken->generateToken()
        );
    }
}
