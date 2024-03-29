<?php

declare(strict_types=1);

namespace App\Repository;

use App\Core\Repository;

class OptionRepository extends Repository
{
    public function setOptionVersion(string $version): bool
    {
        $query = $this->manager->createQuery(
            "UPDATE `option` o SET o.`option_version` = ':version'"
        )
            ->setParameter('version', $version)
            ->getStrQuery();

        return $this->database->dbQuery($query);
    }

    public function setOptionRegistered(): bool
    {
        $query = $this->manager->createQuery(
            'UPDATE `option` o SET o.`option_registered` = 1'
        )->getStrQuery();

        return $this->database->dbQuery($query);
    }

    public function getOptionData(): array
    {
        $array = array();

        $query = $this->manager->createQuery(
            'SELECT * FROM `option`'
        )->getStrQuery();

        $result = $this->database->dbQuery($query);

        if (
            $result !== false
            && is_array($row = $this->database->dbFetchArray($result))
        ) {
            $array['option_version'] = $row['option_version'];
            $array['option_installed'] = (bool) $row['option_installed'];
            $array['option_registered'] = (bool) $row['option_registered'];
        }

        return $array;
    }
}
