<?php

declare(strict_types=1);

namespace App\Controller;

use App\Bundle\Key;
use App\Core\Controller;
use App\Service\ActivateUserService;

class ActivateUserController extends Controller
{
    public function activateUserAction(array $request): array
    {
        $key = new Key();

        $activateUserService = new ActivateUserService($this, $key);

        $array = $activateUserService->activateUserAction(
            (string) ($request['user'] ?? ''),
            (string) ($request['code'] ?? '')
        );

        return $array;
    }
}
