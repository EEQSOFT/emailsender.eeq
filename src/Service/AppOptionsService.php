<?php

declare(strict_types=1);

namespace App\Service;

use App\Bundle\Html;
use App\Core\{Cache, Config, Token};
use App\Validator\AppOptionsValidator;

class AppOptionsService
{
    protected Config $config;
    protected Cache $cache;
    protected Html $html;
    protected Token $csrfToken;
    protected AppOptionsValidator $appOptionsValidator;

    public function __construct(
        Config $config,
        Cache $cache,
        Html $html,
        Token $csrfToken,
        AppOptionsValidator $appOptionsValidator
    ) {
        $this->config = $config;
        $this->cache = $cache;
        $this->html = $html;
        $this->csrfToken = $csrfToken;
        $this->appOptionsValidator = $appOptionsValidator;
    }

    public function appOptionsAction(
        string $adminEmail,
        int $emailsNumber,
        int $timePeriod,
        bool $unsubscribeActive,
        string $unsubscribeUrl,
        string $newsletterName,
        bool $submit,
        string $token
    ): array {
        if ($submit) {
            $this->appOptionsValidator->validate(
                $adminEmail,
                $emailsNumber,
                $timePeriod,
                $unsubscribeUrl,
                $newsletterName,
                $token
            );

            if ($this->appOptionsValidator->isValid()) {
                ob_start();
                include(__DIR__ . '/../../templates/app-options/options.php');
                $content = ob_get_contents();
                ob_end_clean();

                $this->cache->cacheFile(OPTIONS_FILE, $content);

                return array(
                    'content' => 'app-options/options-saved-info.php',
                    'activeMenu' => 'app-options',
                    'title' => 'Information'
                );
            }
        }

        return array(
            'content' => 'app-options/app-options.php',
            'activeMenu' => 'app-options',
            'title' => 'App Options',
            'error' => $this->html->prepareError(
                $this->appOptionsValidator->getError()
            ),
            'version' => $this->config->getAppVersion(),
            'adminEmail' => $adminEmail,
            'emailsNumber' => $emailsNumber,
            'timePeriod' => $timePeriod,
            'unsubscribeActive' => $unsubscribeActive,
            'unsubscribeUrl' => $unsubscribeUrl,
            'newsletterName' => $newsletterName,
            'token' => $this->csrfToken->generateToken()
        );
    }
}
