<?php

declare(strict_types=1);

namespace App\Repository;

use App\Core\Repository;

class UserRepository extends Repository
{
    public function isUserLogin(string $login): bool
    {
        $result = $this->manager->prepare(
            'SELECT u.`user_id` FROM `user` u
            WHERE u.`user_login_canonical` = :loginCanonical'
        )
            ->setParameter('loginCanonical', strtolower($login))
            ->getResult();

        $this->database->execute($result->params);

        foreach ($result->stmt as $row) {
            return (bool) $row['user_id'];
        }

        return false;
    }

    public function isUserEmail(string $email): bool
    {
        $result = $this->manager->prepare(
            'SELECT u.`user_id` FROM `user` u
            WHERE u.`user_email_canonical` = :emailCanonical'
        )
            ->setParameter('emailCanonical', strtolower($email))
            ->getResult();

        $this->database->execute($result->params);

        foreach ($result->stmt as $row) {
            return (bool) $row['user_id'];
        }

        return false;
    }

    public function addRegistrationUserData(
        string $login,
        string $email,
        string $password,
        string $key,
        string $ip,
        string $date
    ): bool {
        $result = $this->manager->prepare(
            'INSERT INTO `user` (
                `user_admin`,
                `user_active`,
                `user_login`,
                `user_login_canonical`,
                `user_email`,
                `user_email_canonical`,
                `user_password`,
                `user_key`,
                `user_ip_added`,
                `user_date_added`
            )
            VALUES (
                1,
                0,
                :login,
                :loginCanonical,
                :email,
                :emailCanonical,
                :password,
                :key,
                :ip,
                :date
            )'
        )
            ->setParameter('login', $login)
            ->setParameter('loginCanonical', strtolower($login))
            ->setParameter('email', $email)
            ->setParameter('emailCanonical', strtolower($email))
            ->setParameter(
                'password',
                password_hash($password, PASSWORD_DEFAULT)
            )
            ->setParameter('key', $key)
            ->setParameter('ip', $ip)
            ->setParameter('date', $date)
            ->getResult();

        return $this->database->execute($result->params);
    }

    public function addAdditionUserData(
        bool $admin,
        string $login,
        string $email,
        string $password,
        string $key,
        string $ip,
        string $date
    ): bool {
        $result = $this->manager->prepare(
            'INSERT INTO `user` (
                `user_admin`,
                `user_active`,
                `user_login`,
                `user_login_canonical`,
                `user_email`,
                `user_email_canonical`,
                `user_password`,
                `user_key`,
                `user_ip_added`,
                `user_date_added`
            )
            VALUES (
                :admin,
                0,
                :login,
                :loginCanonical,
                :email,
                :emailCanonical,
                :password,
                :key,
                :ip,
                :date
            )'
        )
            ->setParameter('admin', (int) $admin)
            ->setParameter('login', $login)
            ->setParameter('loginCanonical', strtolower($login))
            ->setParameter('email', $email)
            ->setParameter('emailCanonical', strtolower($email))
            ->setParameter(
                'password',
                password_hash($password, PASSWORD_DEFAULT)
            )
            ->setParameter('key', $key)
            ->setParameter('ip', $ip)
            ->setParameter('date', $date)
            ->getResult();

        return $this->database->execute($result->params);
    }

    public function setUserActive(int $user, string $key): bool
    {
        $result = $this->manager->prepare(
            'UPDATE `user` u
            SET u.`user_active` = 1, u.`user_key` = :key
            WHERE u.`user_id` = :user'
        )
            ->setParameter('key', $key)
            ->setParameter('user', $user)
            ->getResult();

        return $this->database->execute($result->params);
    }

    public function setUserLoged(int $user, string $ip, string $date): bool
    {
        $result = $this->manager->prepare(
            'UPDATE `user` u
            SET u.`user_ip_loged` = :ip, u.`user_date_loged` = :date
            WHERE u.`user_id` = :user'
        )
            ->setParameter('ip', $ip)
            ->setParameter('date', $date)
            ->setParameter('user', $user)
            ->getResult();

        return $this->database->execute($result->params);
    }

    public function setChangeUserData(
        int $user,
        string $password,
        string $key,
        string $ip,
        string $date
    ): bool {
        $result = $this->manager->prepare(
            'UPDATE `user` u
            SET u.`user_password` = :password, u.`user_key` = :key,
                u.`user_ip_updated` = :ip, u.`user_date_updated` = :date
            WHERE u.`user_id` = :user'
        )
            ->setParameter(
                'password',
                password_hash($password, PASSWORD_DEFAULT)
            )
            ->setParameter('key', $key)
            ->setParameter('ip', $ip)
            ->setParameter('date', $date)
            ->setParameter('user', $user)
            ->getResult();

        return $this->database->execute($result->params);
    }

    public function getActivationUserData(string $login): array
    {
        $array = array();

        $result = $this->manager->prepare(
            'SELECT u.`user_id`, u.`user_active`, u.`user_key` FROM `user` u
            WHERE u.`user_login_canonical` = :loginCanonical'
        )
            ->setParameter('loginCanonical', strtolower($login))
            ->getResult();

        $this->database->execute($result->params);

        foreach ($result->stmt as $row) {
            $array['user_id'] = (int) $row['user_id'];
            $array['user_active'] = (bool) $row['user_active'];
            $array['user_key'] = $row['user_key'];
        }

        return $array;
    }

    public function getLoginUserData(string $login): array
    {
        $array = array();

        $result = $this->manager->prepare(
            'SELECT u.`user_id`, u.`user_admin`, u.`user_active`,
                u.`user_login`, u.`user_email`, u.`user_password`, u.`user_key`
            FROM `user` u
            WHERE u.`user_login_canonical` = :loginCanonical
                OR u.`user_email_canonical` = :emailCanonical'
        )
            ->setParameter('loginCanonical', strtolower($login))
            ->setParameter('emailCanonical', strtolower($login))
            ->getResult();

        $this->database->execute($result->params);

        foreach ($result->stmt as $row) {
            $array['user_id'] = (int) $row['user_id'];
            $array['user_admin'] = (bool) $row['user_admin'];
            $array['user_active'] = (bool) $row['user_active'];
            $array['user_login'] = $row['user_login'];
            $array['user_email'] = $row['user_email'];
            $array['user_password'] = $row['user_password'];
            $array['user_key'] = $row['user_key'];
        }

        return $array;
    }

    public function getResetUserData(string $login): array
    {
        $array = array();

        $result = $this->manager->prepare(
            'SELECT u.`user_active`, u.`user_login`, u.`user_email`,
                u.`user_key` FROM `user` u
            WHERE u.`user_login_canonical` = :loginCanonical
                OR u.`user_email_canonical` = :emailCanonical'
        )
            ->setParameter('loginCanonical', strtolower($login))
            ->setParameter('emailCanonical', strtolower($login))
            ->getResult();

        $this->database->execute($result->params);

        foreach ($result->stmt as $row) {
            $array['user_active'] = (bool) $row['user_active'];
            $array['user_login'] = $row['user_login'];
            $array['user_email'] = $row['user_email'];
            $array['user_key'] = $row['user_key'];
        }

        return $array;
    }

    public function getChangeUserData(string $login): array
    {
        $array = array();

        $result = $this->manager->prepare(
            'SELECT u.`user_id`, u.`user_active`, u.`user_login`,
                u.`user_email`, u.`user_key` FROM `user` u
            WHERE u.`user_login_canonical` = :loginCanonical'
        )
            ->setParameter('loginCanonical', strtolower($login))
            ->getResult();

        $this->database->execute($result->params);

        foreach ($result->stmt as $row) {
            $array['user_id'] = (int) $row['user_id'];
            $array['user_active'] = (bool) $row['user_active'];
            $array['user_login'] = $row['user_login'];
            $array['user_email'] = $row['user_email'];
            $array['user_key'] = $row['user_key'];
        }

        return $array;
    }

    public function getUserList(int $level, int $listLimit): array
    {
        $array = array();

        $result = $this->manager->prepare(
            'SELECT u.`user_id`, u.`user_admin`, u.`user_login`,
                u.`user_email` FROM `user` u
            ORDER BY u.`user_id` DESC LIMIT :start, :limit'
        )
            ->setParameter('start', ($level - 1) * $listLimit)
            ->setParameter('limit', $listLimit)
            ->getResult();

        $this->database->execute($result->params);

        foreach ($result->stmt as $row) {
            $array[$row['user_id']]['user_admin'] = (bool) $row['user_admin'];
            $array[$row['user_id']]['user_login'] = $row['user_login'];
            $array[$row['user_id']]['user_email'] = $row['user_email'];
        }

        return $array;
    }

    public function getUserCount(): int
    {
        $result = $this->manager->prepare(
            'SELECT COUNT(*) AS `count` FROM `user`'
        )->getResult();

        $this->database->execute($result->params);

        foreach ($result->stmt as $row) {
            return (int) $row['count'];
        }

        return 0;
    }

    public function deleteDeletionUserData(int $user): bool
    {
        $result = $this->manager->prepare(
            'DELETE FROM `user` WHERE `user_id` = :user'
        )
            ->setParameter('user', $user)
            ->getResult();

        return $this->database->execute($result->params);
    }
}
