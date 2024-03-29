<?php

declare(strict_types=1);

namespace App\Service;

use App\Bundle\Key;
use App\Controller\ActivateUserController;
use App\Repository\UserRepository;

class ActivateUserService
{
    protected ActivateUserController $activateUserController;
    protected Key $key;

    public function __construct(
        ActivateUserController $activateUserController,
        Key $key
    ) {
        $this->activateUserController = $activateUserController;
        $this->key = $key;
    }

    public function activateUserAction(string $user, string $code): array
    {
        $rm = $this->activateUserController->getManager();
        $ur = $rm->getRepository(UserRepository::class);

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
