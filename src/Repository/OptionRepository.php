<?php

declare(strict_types=1);

namespace App\Repository;

use App\Core\Repository;

class OptionRepository extends Repository
{
    public function setOptionVersion(string $version): bool
    {
        $result = $this->manager->prepare(
            'UPDATE `option` o SET o.`option_version` = :version'
        )
            ->setParameter('version', $version)
            ->getResult();

        return $this->database->execute($result->params);
    }

    public function setOptionRegistered(): bool
    {
        $result = $this->manager->prepare(
            'UPDATE `option` o SET o.`option_registered` = 1'
        )->getResult();

        return $this->database->execute($result->params);
    }

    public function getOptionData(): array
    {
        $array = array();

        $result = $this->manager->prepare(
            'SELECT * FROM `option`'
        )->getResult();

        $this->database->execute($result->params);

        foreach ($result->stmt as $row) {
            $array['option_version'] = $row['option_version'];
            $array['option_installed'] = (bool) $row['option_installed'];
            $array['option_registered'] = (bool) $row['option_registered'];
        }

        return $array;
    }
}
