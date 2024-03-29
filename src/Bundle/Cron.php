<?php

declare(strict_types=1);

namespace App\Bundle;

use App\Core\Cache;

class Cron
{
    protected Cache $cache;
    protected string $cronFile;

    public function __construct()
    {
        $this->cache = new Cache();
        $this->cronFile = CRONTAB_FILE;
    }

    public function exec(string $command): self
    {
        exec($command . ' >/dev/null 2>&1');

        return $this;
    }

    public function appendCronjob(string $cronjob): self
    {
        $crontab = 'SHELL=/bin/sh' . "\n"
            . 'PATH=/usr/local/sbin:/usr/local/bin:'
            . '/usr/sbin:/usr/bin:/sbin:/bin' . "\n"
            . $cronjob . ' >/dev/null 2>&1' . "\n";

        $this->cache->cacheFile($this->cronFile, $crontab);

        $this->exec('crontab ' . $this->cronFile);

        return $this;
    }

    public function removeCrontab(): self
    {
        $this->exec('crontab -r')->removeFile();

        return $this;
    }

    public function removeFile(): self
    {
        if ($this->crontabFileExists()) {
            unlink($this->cronFile);
        }

        return $this;
    }

    private function crontabFileExists(): bool
    {
        return file_exists($this->cronFile);
    }
}
