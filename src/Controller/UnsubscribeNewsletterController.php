<?php

declare(strict_types=1);

namespace App\Controller;

use App\Core\Controller;
use App\Service\UnsubscribeNewsletterService;

class UnsubscribeNewsletterController extends Controller
{
    public function unsubscribeNewsletterAction(array $request): array
    {
        $unsubscribeNewsletterService = new UnsubscribeNewsletterService($this);

        $array = $unsubscribeNewsletterService->unsubscribeNewsletterAction(
            (int) ($request['email'] ?? 0),
            (string) ($request['code'] ?? '')
        );

        return $array;
    }
}
