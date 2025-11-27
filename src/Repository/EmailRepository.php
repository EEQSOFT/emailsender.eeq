<?php

declare(strict_types=1);

namespace App\Repository;

use App\Core\Repository;

class EmailRepository extends Repository
{
    public function isEmailEmail(int $list, string $email): bool
    {
        $result = $this->manager->prepare(
            'SELECT e.`email_id` FROM `email` e
            WHERE e.`list_id` = :list
                AND e.`email_email_canonical` = :emailCanonical'
        )
            ->setParameter('list', $list)
            ->setParameter('emailCanonical', strtolower($email))
            ->getResult();

        $this->database->execute($result->params);

        foreach ($result->stmt as $row) {
            return (bool) $row['email_id'];
        }

        return false;
    }

    public function addEmailData(
        int $list,
        string $name,
        string $email,
        string $ip,
        string $date
    ): bool {
        $result = $this->manager->prepare(
            'INSERT INTO `email` (
                `list_id`,
                `email_name`,
                `email_email`,
                `email_email_canonical`,
                `email_ip_added`,
                `email_date_added`
            )
            VALUES (
                :list,
                :name,
                :email,
                :emailCanonical,
                :ip,
                :date
            )'
        )
            ->setParameter('list', $list)
            ->setParameter('name', $name)
            ->setParameter('emailCanonical', strtolower($email))
            ->setParameter('email', $email)
            ->setParameter('ip', $ip)
            ->setParameter('date', $date)
            ->getResult();

        return $this->database->execute($result->params);
    }

    public function getUnsubscribingEmailData(int $email): array
    {
        $array = array();

        $result = $this->manager->prepare(
            'SELECT e.`email_id`, e.`email_name`, e.`email_email`,
                e.`email_ip_added`, e.`email_date_added` FROM `email` e
            WHERE e.`email_id` = :email'
        )
            ->setParameter('email', $email)
            ->getResult();

        $this->database->execute($result->params);

        foreach ($result->stmt as $row) {
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

        $result = $this->manager->prepare(
            'SELECT e.`email_name`, e.`email_email`,
                e.`email_ip_added`, e.`email_date_added` FROM `email` e
            WHERE e.`list_id` = :list'
        )
            ->setParameter('list', $list)
            ->getResult();

        $this->database->execute($result->params);

        foreach ($result->stmt as $row) {
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

        $result = $this->manager->prepare(
            'SELECT e.`email_id`, e.`email_name`, e.`email_email`,
                e.`email_ip_added`, e.`email_date_added` FROM `email` e
            WHERE e.`list_id` = :list AND e.`email_id` > :email
            ORDER BY e.`email_id` ASC LIMIT :limit'
        )
            ->setParameter('list', $list)
            ->setParameter('email', $email)
            ->setParameter('limit', $listLimit)
            ->getResult();

        $this->database->execute($result->params);

        foreach ($result->stmt as $row) {
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

        $whereEmailCanonical = ($email === '') ? " AND :emailCanonical = ''" :
            ' AND e.`email_email_canonical` = :emailCanonical';

        $result = $this->manager->prepare(
            'SELECT e.`email_id`, e.`email_name`, e.`email_email`
            FROM `email` e
            WHERE e.`list_id` = :list' . $whereEmailCanonical . '
            ORDER BY e.`email_id` DESC LIMIT :start, :limit'
        )
            ->setParameter('list', $list)
            ->setParameter('emailCanonical', strtolower($email))
            ->setParameter('start', ($level - 1) * $listLimit)
            ->setParameter('limit', $listLimit)
            ->getResult();

        $this->database->execute($result->params);

        foreach ($result->stmt as $row) {
            $array[$row['email_id']]['email_name'] = $row['email_name'];
            $array[$row['email_id']]['email_email'] = $row['email_email'];
        }

        return $array;
    }

    public function getEmailCount(int $list, string $email): int
    {
        $whereEmailCanonical = ($email === '') ? " AND :emailCanonical = ''" :
            ' AND e.`email_email_canonical` = :emailCanonical';

        $result = $this->manager->prepare(
            'SELECT COUNT(*) AS `count` FROM `email` e
            WHERE e.`list_id` = :list' . $whereEmailCanonical
        )
            ->setParameter('list', $list)
            ->setParameter('emailCanonical', strtolower($email))
            ->getResult();

        $this->database->execute($result->params);

        foreach ($result->stmt as $row) {
            return (int) $row['count'];
        }

        return 0;
    }

    public function getSendingEmailCount(int $list, int $email): int
    {
        $result = $this->manager->prepare(
            'SELECT COUNT(*) AS `count` FROM `email` e
            WHERE e.`list_id` = :list AND e.`email_id` > :email'
        )
            ->setParameter('list', $list)
            ->setParameter('email', $email)
            ->getResult();

        $this->database->execute($result->params);

        foreach ($result->stmt as $row) {
            return (int) $row['count'];
        }

        return 0;
    }

    public function getIpDateEmailCount(string $ip, string $date): int
    {
        $result = $this->manager->prepare(
            'SELECT COUNT(*) AS `count` FROM `email` e
            WHERE e.`email_ip_added` = :ip AND e.`email_date_added` >= :date'
        )
            ->setParameter('ip', $ip)
            ->setParameter('date', $date)
            ->getResult();

        $this->database->execute($result->params);

        foreach ($result->stmt as $row) {
            return (int) $row['count'];
        }

        return 0;
    }

    public function deleteEmailData(int $email): bool
    {
        $result = $this->manager->prepare(
            'DELETE FROM `email` WHERE `email_id` = :email'
        )
            ->setParameter('email', $email)
            ->getResult();

        return $this->database->execute($result->params);
    }
}
