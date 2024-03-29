<?php

declare(strict_types=1);

namespace App\Repository;

use App\Core\Repository;

class ListRepository extends Repository
{
    public function isListName(string $name): bool
    {
        $query = $this->manager->createQuery(
            "SELECT l.`list_id` FROM `list` l
            WHERE l.`list_name` = ':name'"
        )
            ->setParameter('name', $name)
            ->getStrQuery();

        $result = $this->database->dbQuery($query);

        return (bool) $this->database->dbFetchArray($result);
    }

    public function addListData(string $name): bool
    {
        $query = $this->manager->createQuery(
            "INSERT INTO `list` (`list_name`) VALUES (':name')"
        )
            ->setParameter('name', $name)
            ->getStrQuery();

        return $this->database->dbQuery($query);
    }

    public function getListId(string $name): int
    {
        $query = $this->manager->createQuery(
            "SELECT l.`list_id` FROM `list` l
            WHERE l.`list_name` = ':name'"
        )
            ->setParameter('name', $name)
            ->getStrQuery();

        $result = $this->database->dbQuery($query);

        if (is_array($row = $this->database->dbFetchArray($result))) {
            return (int) $row['list_id'];
        }

        return 0;
    }

    public function getExportListData(int $list): array
    {
        $array = array();

        $query = $this->manager->createQuery(
            'SELECT l.`list_name` FROM `list` l
            WHERE l.`list_id` = :list'
        )
            ->setParameter('list', $list)
            ->getStrQuery();

        $result = $this->database->dbQuery($query);

        if (is_array($row = $this->database->dbFetchArray($result))) {
            $array['list_name'] = $row['list_name'];
        }

        return $array;
    }

    public function getListList(int $level = 1, int $listLimit = 100000): array
    {
        $array = array();

        $query = $this->manager->createQuery(
            'SELECT l.`list_id`, l.`list_name` FROM `list` l
            ORDER BY l.`list_name` ASC LIMIT :start, :limit'
        )
            ->setParameter('start', ($level - 1) * $listLimit)
            ->setParameter('limit', $listLimit)
            ->getStrQuery();

        $result = $this->database->dbQuery($query);

        while (
            $result !== false
            && is_array($row = $this->database->dbFetchArray($result))
        ) {
            $array[$row['list_id']]['list_name'] = $row['list_name'];
        }

        return $array;
    }

    public function deleteEmailListData(int $list): bool
    {
        $this->database->dbStartTransaction();

        $query = $this->manager->createQuery(
            'DELETE FROM `email` WHERE `list_id` = :list'
        )
            ->setParameter('list', $list)
            ->getStrQuery();

        $result = $this->database->dbQuery($query);

        $query2 = $this->manager->createQuery(
            'DELETE FROM `list` WHERE `list_id` = :list'
        )
            ->setParameter('list', $list)
            ->getStrQuery();

        $result2 = $this->database->dbQuery($query2);

        if ($result && $result2) {
            $this->database->dbCommit();

            return true;
        } else {
            $this->database->dbRollback();
        }

        return false;
    }
}
