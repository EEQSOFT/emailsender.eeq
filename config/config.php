<?php

session_start();

ini_set('register_globals', '0');
header('Content-Type: text/html; charset=utf-8');
date_default_timezone_set('UTC');
set_time_limit(180);
ini_set('memory_limit', '1024M');
ini_set('post_max_size', '200M');
ini_set('upload_max_filesize', '100M');

const CONFIG_FILE = __DIR__ . '/config.php';
const DATABASE_FILE = __DIR__ . '/database.php';
const SETTINGS_FILE = __DIR__ . '/settings.php';
const OPTIONS_FILE = __DIR__ . '/options.php';
const CRONTAB_FILE = __DIR__ . '/../cron/crontab.txt';
const CRONJOB_FILE = __DIR__ . '/../cron/cronjob.php';
const AUTOLOAD_FILE = __DIR__ . '/../src/autoload.php';

const EXPORT_DIR = __DIR__ . '/../data/Export/';
const IMPORT_DIR = __DIR__ . '/../data/Import/';
