<?php

declare(strict_types=1);

namespace App\Repository;

use App\Core\Repository;

class SendRepository extends Repository
{
    public function setSendEmail(int $email): bool
    {
        $query = $this->manager->createQuery(
            'UPDATE `send` s SET s.`email_id` = :email'
        )
            ->setParameter('email', $email)
            ->getStrQuery();

        return $this->database->dbQuery($query);
    }

    public function setSendData(
        int $list,
        int $email,
        int $text,
        int $count
    ): bool {
        $query = $this->manager->createQuery(
            'UPDATE `send` s
            SET s.`list_id` = :list, s.`email_id` = :email,
                s.`text_id` = :text, s.`send_count` = :count'
        )
            ->setParameter('list', $list)
            ->setParameter('email', $email)
            ->setParameter('text', $text)
            ->setParameter('count', $count)
            ->getStrQuery();

        return $this->database->dbQuery($query);
    }

    public function getSendData(): array
    {
        $array = array();

        $query = $this->manager->createQuery(
            'SELECT * FROM `send`'
        )->getStrQuery();

        $result = $this->database->dbQuery($query);

        if (is_array($row = $this->database->dbFetchArray($result))) {
            $array['list_id'] = (int) $row['list_id'];
            $array['email_id'] = (int) $row['email_id'];
            $array['text_id'] = (int) $row['text_id'];
            $array['send_count'] = (int) $row['send_count'];
        }

        return $array;
    }
}
