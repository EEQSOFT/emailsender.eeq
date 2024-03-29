<?php

declare(strict_types=1);

namespace App\Repository;

use App\Core\Repository;

class TextRepository extends Repository
{
    public function addTextData(string $subject, string $message): bool
    {
        $query = $this->manager->createQuery(
            "INSERT INTO `text` (`text_subject`, `text_message`)
            VALUES (':subject', ':message')"
        )
            ->setParameter('subject', $subject)
            ->setParameter('message', $message)
            ->getStrQuery();

        return $this->database->dbQuery($query);
    }

    public function setTextData(
        int $text,
        string $subject,
        string $message
    ): bool {
        $query = $this->manager->createQuery(
            "UPDATE `text` t
            SET t.`text_subject` = ':subject', t.`text_message` = ':message'
            WHERE t.`text_id` = :text"
        )
            ->setParameter('subject', $subject)
            ->setParameter('message', $message)
            ->setParameter('text', $text)
            ->getStrQuery();

        return $this->database->dbQuery($query);
    }

    public function getTextData(int $text): array
    {
        $array = array();

        $query = $this->manager->createQuery(
            'SELECT t.`text_subject`, t.`text_message` FROM `text` t
            WHERE t.`text_id` = :text'
        )
            ->setParameter('text', $text)
            ->getStrQuery();

        $result = $this->database->dbQuery($query);

        if (is_array($row = $this->database->dbFetchArray($result))) {
            $array['text_subject'] = $row['text_subject'];
            $array['text_message'] = $row['text_message'];
        }

        return $array;
    }

    public function getTextList(int $level = 1, int $listLimit = 100000): array
    {
        $array = array();

        $query = $this->manager->createQuery(
            'SELECT t.`text_id`, t.`text_subject` FROM `text` t
            ORDER BY t.`text_subject` ASC, t.`text_id` DESC
            LIMIT :start, :limit'
        )
            ->setParameter('start', ($level - 1) * $listLimit)
            ->setParameter('limit', $listLimit)
            ->getStrQuery();

        $result = $this->database->dbQuery($query);

        while (
            $result !== false
            && is_array($row = $this->database->dbFetchArray($result))
        ) {
            $array[$row['text_id']]['text_subject'] = $row['text_subject'];
        }

        return $array;
    }

    public function getTextCount(): int
    {
        $query = $this->manager->createQuery(
            'SELECT COUNT(*) AS `count` FROM `text`'
        )->getStrQuery();

        $result = $this->database->dbQuery($query);

        if (is_array($row = $this->database->dbFetchArray($result))) {
            return (int) $row['count'];
        }

        return 0;
    }

    public function deleteTextData(int $text): bool
    {
        $query = $this->manager->createQuery(
            'DELETE FROM `text` WHERE `text_id` = :text'
        )
            ->setParameter('text', $text)
            ->getStrQuery();

        return $this->database->dbQuery($query);
    }
}
