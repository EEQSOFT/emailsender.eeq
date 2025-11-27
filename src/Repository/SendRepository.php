<?php

declare(strict_types=1);

namespace App\Repository;

use App\Core\Repository;

class SendRepository extends Repository
{
    public function setSendEmail(int $email): bool
    {
        $result = $this->manager->prepare(
            'UPDATE `send` s SET s.`email_id` = :email'
        )
            ->setParameter('email', $email)
            ->getResult();

        return $this->database->execute($result->params);
    }

    public function setSendData(
        int $list,
        int $email,
        int $text,
        int $count
    ): bool {
        $result = $this->manager->prepare(
            'UPDATE `send` s
            SET s.`list_id` = :list, s.`email_id` = :email,
                s.`text_id` = :text, s.`send_count` = :count'
        )
            ->setParameter('list', $list)
            ->setParameter('email', $email)
            ->setParameter('text', $text)
            ->setParameter('count', $count)
            ->getResult();

        return $this->database->execute($result->params);
    }

    public function getSendData(): array
    {
        $array = array();

        $result = $this->manager->prepare(
            'SELECT * FROM `send`'
        )->getResult();

        $this->database->execute($result->params);

        foreach ($result->stmt as $row) {
            $array['list_id'] = (int) $row['list_id'];
            $array['email_id'] = (int) $row['email_id'];
            $array['text_id'] = (int) $row['text_id'];
            $array['send_count'] = (int) $row['send_count'];
        }

        return $array;
    }
}
