<?php

declare(strict_types=1);

namespace App\Bundle;

class Html
{
    public function prepareError(?array $array): string
    {
        $error = '';

        if (is_array($array)) {
            foreach ($array as $key => $value) {
                $error .= htmlspecialchars($value) . '<br />';
            }

            $error .= "\n";
        }

        return $error;
    }

    public function preparePageNavigator(
        string $url,
        int $level,
        int $listLimit,
        int $count,
        int $levelLimit
    ): string {
        $pageNavigator = '';

        if ($count > $listLimit) {
            $minLevel = 1;
            $maxLevel = number_format($count / $listLimit, 0, '.', '');
            $number = number_format($count / $listLimit, 2, '.', '');
            $maxLevel = ($number > $maxLevel) ? $maxLevel + 1 : $maxLevel;
            $number = $level - $levelLimit;
            $fromLevel = ($number < $minLevel) ? $minLevel : $number;
            $number = $level + $levelLimit;
            $toLevel = ($number > $maxLevel) ? $maxLevel : $number;
            $previousLevel = $level - 1;
            $nextLevel = $level + 1;
            $url = htmlspecialchars($url);

            $pageNavigator .= '<ul class="pagination">';

            if ($maxLevel > $levelLimit) {
                $pageNavigator .= ($level > $minLevel) ? '<li><a href="' . $url
                    . $minLevel . '">...</a></li>' : '';
            }

            $pageNavigator .= ($level > $minLevel) ? '<li><a href="' . $url
                . $previousLevel . '">&laquo;</a></li>' : '';

            for ($i = $fromLevel; $i <= $toLevel; $i++) {
                $pageNavigator .= ($i !== $level) ? '<li><a href="' . $url
                    . $i . '">' . $i . '</a></li>' : '<li class="active">'
                    . '<a href="#">' . $i . '</a></li>';
            }

            $pageNavigator .= ($level < $maxLevel) ? '<li><a href="' . $url
                . $nextLevel . '">&raquo;</a></li>' : '';

            if ($maxLevel > $levelLimit) {
                $pageNavigator .= ($level < $maxLevel) ? '<li><a href="' . $url
                    . $maxLevel . '">...</a></li>' : '';
            }

            $pageNavigator .= '</ul>' . "\n";
        }

        return $pageNavigator;
    }
}
