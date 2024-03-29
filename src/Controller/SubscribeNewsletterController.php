<?php

declare(strict_types=1);

namespace App\Controller;

use App\Bundle\Html;
use App\Core\{Config, Controller, Token};
use App\Service\SubscribeNewsletterService;
use App\Validator\SubscribeNewsletterValidator;

class SubscribeNewsletterController extends Controller
{
    public function subscribeNewsletterAction(
        array $request,
        array $session,
        array $options
    ): array {
        $config = new Config();
        $html = new Html();
        $csrfToken = new Token();
        $subscribeNewsletterValidator = new SubscribeNewsletterValidator(
            $csrfToken,
            $this->getManager()
        );

        $subscribeNewsletterService = new SubscribeNewsletterService(
            $this,
            $config,
            $html,
            $csrfToken,
            $subscribeNewsletterValidator
        );

        $array = $subscribeNewsletterService->subscribeNewsletterAction(
            (string) ($request['name'] ?? ''),
            (string) ($request['email'] ?? ''),
            (bool) ($request['submit'] ?? false),
            (string) ($request['token'] ?? ''),
            $options['newsletter_name']
        );

        return $array;
    }
}
