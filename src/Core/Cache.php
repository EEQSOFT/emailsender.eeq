<?php

declare(strict_types=1);

namespace App\Core;

class Cache
{
    public function cacheFile(string $cacheFile, string $content): void
    {
        if (file_exists($cacheFile)) {
            $file = fopen($cacheFile, 'r+');
        } else {
            $file = fopen($cacheFile, 'w');
        }

        if (is_resource($file)) {
            if (flock($file, LOCK_EX)) {
                ftruncate($file, 0);
                fwrite($file, $content);
                flock($file, LOCK_UN);
            }

            fclose($file);
        }
    }
}
