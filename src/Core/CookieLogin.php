<?php

declare(strict_types=1);

namespace App\Core;

use App\Core\{Config, Controller};

class CookieLogin extends Controller
{
    protected Config $config;

    public function __construct(Config $config)
    {
        $this->config = $config;
    }

    public function setCookieLogin(): void
    {
        if (
            ($_SESSION['user'] ?? '') === ''
            && ($_COOKIE['cookie_login'] ?? '') !== ''
        ) {
            $this->setManager();
            $this->setSessionLogin();
        }
    }

    private function setSessionLogin(): void
    {
        $cookie = explode(';', $_COOKIE['cookie_login']);

        $userData = $this->getUserData($cookie[0] ?? '', $cookie[1] ?? '');

        if ($userData['user_active'] ?? false) {
            $query = $this->manager->createQuery(
                "UPDATE `user` u
                SET u.`user_ip_loged` = ':ip', u.`user_date_loged` = ':date'
                WHERE u.`user_id` = :user"
            )
                ->setParameter('ip', $this->config->getRemoteAddress())
                ->setParameter('date', $this->config->getDateTimeNow())
                ->setParameter('user', $userData['user_id'])
                ->getStrQuery();

            $userLoged = $this->database->dbQuery($query);

            if ($userLoged) {
                $_SESSION['id'] = $userData['user_id'];
                $_SESSION['admin'] = $userData['user_admin'];
                $_SESSION['user'] = $userData['user_login'];
            }
        }
    }

    private function getUserData(string $login, string $password): array
    {
        $arrayResult = array();

        $query = $this->manager->createQuery(
            "SELECT u.`user_id`, u.`user_admin`, u.`user_active`,
                u.`user_login` FROM `user` u
            WHERE u.`user_login_canonical` = ':loginCanonical'
                AND u.`user_password` = ':password'"
        )
            ->setParameter('loginCanonical', strtolower($login))
            ->setParameter('password', $password)
            ->getStrQuery();

        $result = $this->database->dbQuery($query);

        if (is_array($row = $this->database->dbFetchArray($result))) {
            $arrayResult['user_id'] = (int) $row['user_id'];
            $arrayResult['user_admin'] = (bool) $row['user_admin'];
            $arrayResult['user_active'] = (bool) $row['user_active'];
            $arrayResult['user_login'] = $row['user_login'];
        }

        return $arrayResult;
    }
}
