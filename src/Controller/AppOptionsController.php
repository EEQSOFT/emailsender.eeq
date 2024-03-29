<?php

declare(strict_types=1);

namespace App\Controller;

use App\Bundle\Html;
use App\Core\{Cache, Config, Token};
use App\Service\AppOptionsService;
use App\Validator\AppOptionsValidator;

class AppOptionsController
{
    public function appOptionsAction(
        array $request,
        array $session,
        array $options
    ): array {
        $config = new Config();
        $cache = new Cache();
        $html = new Html();
        $csrfToken = new Token();
        $appOptionsValidator = new AppOptionsValidator($csrfToken);

        $appOptionsService = new AppOptionsService(
            $config,
            $cache,
            $html,
            $csrfToken,
            $appOptionsValidator
        );

        $array = $appOptionsService->appOptionsAction(
            (string) ($request['admin_email'] ?? $options['admin_email']),
            (int) ($request['emails_number'] ?? $options['emails_number']),
            (int) ($request['time_period'] ?? $options['time_period']),
            (bool) (
                $request['unsubscribe_active'] ?? $options['unsubscribe_active']
            ),
            (string) (
                $request['unsubscribe_url'] ?? $options['unsubscribe_url']
            ),
            (string) (
                $request['newsletter_name'] ?? $options['newsletter_name']
            ),
            (bool) ($request['submit'] ?? false),
            (string) ($request['token'] ?? '')
        );

        return $array;
    }
}
