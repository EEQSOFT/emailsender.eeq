<?php

declare(strict_types=1);

namespace App\Service;

use App\Bundle\Key;
use App\Core\Manager;
use App\Repository\UserRepository;

class ActivateUserService
{
    protected Manager $rm;
    protected Key $key;

    public function __construct(
        Manager $rm,
        Key $key
    ) {
        $this->rm = $rm;
        $this->key = $key;
    }

    public function activateUserAction(string $user, string $code): array
    {
        $ur = $this->rm->getRepository(UserRepository::class);

        if ($user !== '' && $code !== '') {
            $activationUserData = $ur->getActivationUserData($user);

            if ($code !== ($activationUserData['user_key'] ?? '')) {
                return array(
                    'content' => 'activate-user/code-not-valid-info.php',
                    'activeMenu' => 'activate-user',
                    'title' => 'Information'
                );
            }

            if ($activationUserData['user_active']) {
                return array(
                    'content' => 'activate-user/account-is-active-info.php',
                    'activeMenu' => 'activate-user',
                    'title' => 'Information'
                );
            }

            $key = $this->key->generateKey();

            $userActiveSet = $ur->setUserActive(
                $activationUserData['user_id'],
                $key
            );

            return array(
                'content' => 'activate-user/account-activation-info.php',
                'activeMenu' => 'activate-user',
                'title' => 'Information',
                'userActiveSet' => $userActiveSet
            );
        }

        return array(
            'content' => 'activate-user/activate-user.php',
            'activeMenu' => 'activate-user',
            'title' => 'User Activation'
        );
    }
}
