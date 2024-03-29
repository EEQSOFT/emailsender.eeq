<?php

declare(strict_types=1);

namespace App\Core;

class Data
{
    public function prepareInput(array $array): array
    {
        foreach ($array as $key => $value) {
            if (is_string($value)) {
                $array[$key] = trim($value);
            }
        }

        return $array;
    }

    public function prepareOutput(array $array): array
    {
        foreach ($array as $key => $value) {
            if (
                preg_match('/^error([0-9]*)$/', $key) !== 1
                && preg_match('/^pageNavigator([0-9]*)$/', $key) !== 1
                && is_string($value)
            ) {
                $array[$key] = htmlspecialchars($value);
            } elseif (is_array($value)) {
                foreach ($value as $key2 => $value2) {
                    if (is_string($value2)) {
                        $array[$key][$key2] = htmlspecialchars($value2);
                    } elseif (is_array($value2)) {
                        foreach ($value2 as $key3 => $value3) {
                            if (is_string($value3)) {
                                $array[$key][$key2][$key3] =
                                    htmlspecialchars($value3);
                            }
                        }
                    }
                }
            }
        }

        return $array;
    }
}
