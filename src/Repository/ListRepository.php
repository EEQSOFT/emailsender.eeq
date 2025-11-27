<?php

declare(strict_types=1);

namespace App\Repository;

use App\Core\Repository;

class ListRepository extends Repository
{
    public function isListName(string $name): bool
    {
        $result = $this->manager->prepare(
            'SELECT l.`list_id` FROM `list` l
            WHERE l.`list_name` = :name'
        )
            ->setParameter('name', $name)
            ->getResult();

        $this->database->execute($result->params);

        foreach ($result->stmt as $row) {
            return (bool) $row['list_id'];
        }

        return false;
    }

    public function addListData(string $name): bool
    {
        $result = $this->manager->prepare(
            'INSERT INTO `list` (`list_name`) VALUES (:name)'
        )
            ->setParameter('name', $name)
            ->getResult();

        return $this->database->execute($result->params);
    }

    public function getListId(string $name): int
    {
        $result = $this->manager->prepare(
            'SELECT l.`list_id` FROM `list` l
            WHERE l.`list_name` = :name'
        )
            ->setParameter('name', $name)
            ->getResult();

        $this->database->execute($result->params);

        foreach ($result->stmt as $row) {
            return (int) $row['list_id'];
        }

        return 0;
    }

    public function getExportListData(int $list): array
    {
        $array = array();

        $result = $this->manager->prepare(
            'SELECT l.`list_name` FROM `list` l
            WHERE l.`list_id` = :list'
        )
            ->setParameter('list', $list)
            ->getResult();

        $this->database->execute($result->params);

        foreach ($result->stmt as $row) {
            $array['list_name'] = $row['list_name'];
        }

        return $array;
    }

    public function getListList(int $level = 1, int $listLimit = 100000): array
    {
        $array = array();

        $result = $this->manager->prepare(
            'SELECT l.`list_id`, l.`list_name` FROM `list` l
            ORDER BY l.`list_name` ASC LIMIT :start, :limit'
        )
            ->setParameter('start', ($level - 1) * $listLimit)
            ->setParameter('limit', $listLimit)
            ->getResult();

        $this->database->execute($result->params);

        foreach ($result->stmt as $row) {
            $array[$row['list_id']]['list_name'] = $row['list_name'];
        }

        return $array;
    }

    public function deleteEmailListData(int $list): bool
    {
        $this->database->beginTransaction();

        $result = $this->manager->prepare(
            'DELETE FROM `email` WHERE `list_id` = :list'
        )
            ->setParameter('list', $list)
            ->getResult();

        $ok = $this->database->execute($result->params);

        $result = $this->manager->prepare(
            'DELETE FROM `list` WHERE `list_id` = :list'
        )
            ->setParameter('list', $list)
            ->getResult();

        $ok2 = $this->database->execute($result->params);

        if ($ok && $ok2) {
            $this->database->commit();

            return true;
        } else {
            $this->database->rollBack();
        }

        return false;
    }
}
