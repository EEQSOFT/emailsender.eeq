<?php

declare(strict_types=1);

namespace App\Repository;

use App\Core\Repository;

class TextRepository extends Repository
{
    public function addTextData(string $subject, string $message): bool
    {
        $result = $this->manager->prepare(
            'INSERT INTO `text` (`text_subject`, `text_message`)
            VALUES (:subject, :message)'
        )
            ->setParameter('subject', $subject)
            ->setParameter('message', $message)
            ->getResult();

        return $this->database->execute($result->params);
    }

    public function setTextData(
        int $text,
        string $subject,
        string $message
    ): bool {
        $result = $this->manager->prepare(
            'UPDATE `text` t
            SET t.`text_subject` = :subject, t.`text_message` = :message
            WHERE t.`text_id` = :text'
        )
            ->setParameter('subject', $subject)
            ->setParameter('message', $message)
            ->setParameter('text', $text)
            ->getResult();

        return $this->database->execute($result->params);
    }

    public function getTextData(int $text): array
    {
        $array = array();

        $result = $this->manager->prepare(
            'SELECT t.`text_subject`, t.`text_message` FROM `text` t
            WHERE t.`text_id` = :text'
        )
            ->setParameter('text', $text)
            ->getResult();

        $this->database->execute($result->params);

        foreach ($result->stmt as $row) {
            $array['text_subject'] = $row['text_subject'];
            $array['text_message'] = $row['text_message'];
        }

        return $array;
    }

    public function getTextList(int $level = 1, int $listLimit = 100000): array
    {
        $array = array();

        $result = $this->manager->prepare(
            'SELECT t.`text_id`, t.`text_subject` FROM `text` t
            ORDER BY t.`text_subject` ASC, t.`text_id` DESC
            LIMIT :start, :limit'
        )
            ->setParameter('start', ($level - 1) * $listLimit)
            ->setParameter('limit', $listLimit)
            ->getResult();

        $this->database->execute($result->params);

        foreach ($result->stmt as $row) {
            $array[$row['text_id']]['text_subject'] = $row['text_subject'];
        }

        return $array;
    }

    public function getTextCount(): int
    {
        $result = $this->manager->prepare(
            'SELECT COUNT(*) AS `count` FROM `text`'
        )->getResult();

        $this->database->execute($result->params);

        foreach ($result->stmt as $row) {
            return (int) $row['count'];
        }

        return 0;
    }

    public function deleteTextData(int $text): bool
    {
        $result = $this->manager->prepare(
            'DELETE FROM `text` WHERE `text_id` = :text'
        )
            ->setParameter('text', $text)
            ->getResult();

        return $this->database->execute($result->params);
    }
}
