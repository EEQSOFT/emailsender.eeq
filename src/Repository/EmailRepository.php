<?php

declare(strict_types=1);

namespace App\Repository;

use App\Core\Repository;

class EmailRepository extends Repository
{
    public function isEmailEmail(int $list, string $email): bool
    {
        $query = $this->manager->createQuery(
            "SELECT e.`email_id` FROM `email` e
            WHERE e.`list_id` = :list
                AND e.`email_email_canonical` = ':emailCanonical'"
        )
            ->setParameter('list', $list)
            ->setParameter('emailCanonical', strtolower($email))
            ->getStrQuery();

        $result = $this->database->dbQuery($query);

        return (bool) $this->database->dbFetchArray($result);
    }

    public function addEmailData(
        int $list,
        string $name,
        string $email,
        string $ip,
        string $date
    ): bool {
        $query = $this->manager->createQuery(
            "INSERT INTO `email` (
                `list_id`,
                `email_name`,
                `email_email`,
                `email_email_canonical`,
                `email_ip_added`,
                `email_date_added`
            )
            VALUES (
                :list,
                ':name',
                ':email',
                ':emailCanonical',
                ':ip',
                ':date'
            )"
        )
            ->setParameter('list', $list)
            ->setParameter('name', $name)
            ->setParameter('emailCanonical', strtolower($email))
            ->setParameter('email', $email)
            ->setParameter('ip', $ip)
            ->setParameter('date', $date)
            ->getStrQuery();

        return $this->database->dbQuery($query);
    }

    public function getUnsubscribingEmailData(int $email): array
    {
        $array = array();

        $query = $this->manager->createQuery(
            'SELECT e.`email_id`, e.`email_name`, e.`email_email`,
                e.`email_ip_added`, e.`email_date_added` FROM `email` e
            WHERE e.`email_id` = :email'
        )
            ->setParameter('email', $email)
            ->getStrQuery();

        $result = $this->database->dbQuery($query);

        if (is_array($row = $this->database->dbFetchArray($result))) {
            $array['email_id'] = $row['email_id'];
            $array['email_name'] = $row['email_name'];
            $array['email_email'] = $row['email_email'];
            $array['email_ip_added'] = $row['email_ip_added'];
            $array['email_date_added'] = $row['email_date_added'];
        }

        return $array;
    }

    public function getExportEmailList(int $list): array
    {
        $array = array();
        $i = 0;

        $query = $this->manager->createQuery(
            'SELECT e.`email_name`, e.`email_email`,
                e.`email_ip_added`, e.`email_date_added` FROM `email` e
            WHERE e.`list_id` = :list'
        )
            ->setParameter('list', $list)
            ->getStrQuery();

        $result = $this->database->dbQuery($query);

        while (
            $result !== false
            && is_array($row = $this->database->dbFetchArray($result))
        ) {
            $array[++$i]['email_name'] = $row['email_name'];
            $array[$i]['email_email'] = $row['email_email'];
            $array[$i]['email_ip_added'] = $row['email_ip_added'];
            $array[$i]['email_date_added'] = $row['email_date_added'];
        }

        return $array;
    }

    public function getCronjobEmailList(
        int $list,
        int $email,
        int $listLimit
    ): array {
        $array = array();

        $query = $this->manager->createQuery(
            'SELECT e.`email_id`, e.`email_name`, e.`email_email`,
                e.`email_ip_added`, e.`email_date_added` FROM `email` e
            WHERE e.`list_id` = :list AND e.`email_id` > :email
            ORDER BY e.`email_id` ASC LIMIT :limit'
        )
            ->setParameter('list', $list)
            ->setParameter('email', $email)
            ->setParameter('limit', $listLimit)
            ->getStrQuery();

        $result = $this->database->dbQuery($query);

        while (
            $result !== false
            && is_array($row = $this->database->dbFetchArray($result))
        ) {
            $array[$row['email_id']]['email_name'] = $row['email_name'];
            $array[$row['email_id']]['email_email'] = $row['email_email'];
            $array[$row['email_id']]['email_ip_added'] =
                $row['email_ip_added'];
            $array[$row['email_id']]['email_date_added'] =
                $row['email_date_added'];
        }

        return $array;
    }

    public function getEmailList(
        int $list,
        string $email,
        int $level,
        int $listLimit
    ): array {
        $array = array();

        $whereEmailCanonical = ($email === '') ? '' :
            " AND e.`email_email_canonical` = ':emailCanonical'";

        $query = $this->manager->createQuery(
            'SELECT e.`email_id`, e.`email_name`, e.`email_email`
            FROM `email` e
            WHERE e.`list_id` = :list' . $whereEmailCanonical . '
            ORDER BY e.`email_id` DESC LIMIT :start, :limit'
        )
            ->setParameter('list', $list)
            ->setParameter('emailCanonical', strtolower($email))
            ->setParameter('start', ($level - 1) * $listLimit)
            ->setParameter('limit', $listLimit)
            ->getStrQuery();

        $result = $this->database->dbQuery($query);

        while (
            $result !== false
            && is_array($row = $this->database->dbFetchArray($result))
        ) {
            $array[$row['email_id']]['email_name'] = $row['email_name'];
            $array[$row['email_id']]['email_email'] = $row['email_email'];
        }

        return $array;
    }

    public function getEmailCount(int $list, string $email): int
    {
        $whereEmailCanonical = ($email === '') ? '' :
            " AND e.`email_email_canonical` = ':emailCanonical'";

        $query = $this->manager->createQuery(
            'SELECT COUNT(*) AS `count` FROM `email` e
            WHERE e.`list_id` = :list' . $whereEmailCanonical
        )
            ->setParameter('list', $list)
            ->setParameter('emailCanonical', strtolower($email))
            ->getStrQuery();

        $result = $this->database->dbQuery($query);

        if (is_array($row = $this->database->dbFetchArray($result))) {
            return (int) $row['count'];
        }

        return 0;
    }

    public function getSendingEmailCount(int $list, int $email): int
    {
        $query = $this->manager->createQuery(
            'SELECT COUNT(*) AS `count` FROM `email` e
            WHERE e.`list_id` = :list AND e.`email_id` > :email'
        )
            ->setParameter('list', $list)
            ->setParameter('email', $email)
            ->getStrQuery();

        $result = $this->database->dbQuery($query);

        if (is_array($row = $this->database->dbFetchArray($result))) {
            return (int) $row['count'];
        }

        return 0;
    }

    public function deleteEmailData(int $email): bool
    {
        $query = $this->manager->createQuery(
            'DELETE FROM `email` WHERE `email_id` = :email'
        )
            ->setParameter('email', $email)
            ->getStrQuery();

        return $this->database->dbQuery($query);
    }
}
