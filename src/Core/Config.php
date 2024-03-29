<?php

declare(strict_types=1);

namespace App\Core;

class Config
{
    protected string $url;
    protected int $serverPort;
    protected string $serverName;
    protected string $serverDomain;
    protected string $serverEmail;
    protected string $adminEmail;
    protected string $remoteAddress;
    protected string $dateTimeNow;
    protected string $appVersion;

    public function __construct()
    {
        $options = require(OPTIONS_FILE);

        $this->serverPort = (int) $_SERVER['SERVER_PORT'];
        $this->url = 'http' . (($this->serverPort === 443) ? 's' : '') . '://'
            . $_SERVER['HTTP_HOST'];
        $this->serverName = $_SERVER['SERVER_NAME'];
        $this->serverDomain = preg_replace('/^www\./i', '', $this->serverName);
        $this->serverEmail = 'contact@' . $this->serverDomain;
        $this->adminEmail = $options['admin_email'] ?? $this->serverEmail;
        $this->remoteAddress = $_SERVER['REMOTE_ADDR'];
        $this->dateTimeNow = date('Y-m-d H:i:s');
        $this->appVersion = $options['version'];
    }

    public function getUrl(): string
    {
        return $this->url;
    }

    public function getServerPort(): int
    {
        return $this->serverPort;
    }

    public function getServerName(): string
    {
        return $this->serverName;
    }

    public function getServerDomain(): string
    {
        return $this->serverDomain;
    }

    public function getServerEmail(): string
    {
        return $this->serverEmail;
    }

    public function getAdminEmail(): string
    {
        return $this->adminEmail;
    }

    public function getRemoteAddress(): string
    {
        return $this->remoteAddress;
    }

    public function getDateTimeNow(): string
    {
        return $this->dateTimeNow;
    }

    public function getAppVersion(): string
    {
        return $this->appVersion;
    }
}
