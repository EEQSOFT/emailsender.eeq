<?php

declare(strict_types=1);

require(__DIR__ . '/../config/config.php');
require(__DIR__ . '/../src/autoload.php');

use App\Core\{Cache, Config, Database, Manager};
use App\Repository\OptionRepository;

$config = new Config();
$db = new Database();
$rm = new Manager($db);
$cache = new Cache();

$options = require(OPTIONS_FILE);
$database = require(DATABASE_FILE);

setcookie('cookie_login', '', 0, '/', $config->getServerName());

session_destroy();

if (!chmod(OPTIONS_FILE, octdec('666'))) {
?>
    <link rel="stylesheet" href="<?= $config->getUrl() ?>/css/bootstrap.min.css" />

    <div class="container">
        <h2>Information</h2>

        <div class="alert alert-info">
            <strong>Info!</strong> Change the permissions of the "config/options.php" file to 666.
        </div>
    </div>
<?php

    exit;
}

$db->dbConnect();

$or = $rm->getRepository(OptionRepository::class);

$optionData = $or->getOptionData();

if ($options['installed']) {
?>
    <link rel="stylesheet" href="<?= $config->getUrl() ?>/css/bootstrap.min.css" />

    <div class="container">
        <h2>Information</h2>

        <div class="alert alert-warning">
            <strong>Warning!</strong> The script database is already installed.
        </div>
    </div>

    <script>
        setTimeout(function() {
            window.location = '<?= $config->getUrl() ?>/log-in';
        }, 9000);
    </script>
<?php

    exit;
} elseif (
    !empty($optionData)
    && $optionData['option_installed']
    && iv($options['version']) > iv($optionData['option_version'])
) {
    if (
        iv($optionData['option_version']) >= iv('0.1.0')
        && iv($optionData['option_version']) < iv('0.1.1')
    ) {
        if ($or->setOptionVersion('0.1.1')) {
            $optionData['option_version'] = '0.1.1';
        }
    }

    if (
        iv($optionData['option_version']) >= iv('0.1.1')
        && iv($optionData['option_version']) < iv('0.1.2')
    ) {
        if ($or->setOptionVersion('0.1.2')) {
            $optionData['option_version'] = '0.1.2';
        }
    }

    $cache->cacheFile(
        OPTIONS_FILE,
        str_replace(
            "'installed' => false",
            "'installed' => true",
            file_get_contents(OPTIONS_FILE)
        )
    );

    if (!$optionData['option_registered']) {
?>
        <link rel="stylesheet" href="<?= $config->getUrl() ?>/css/bootstrap.min.css" />

        <div class="container">
            <h2>Information</h2>

            <div class="alert alert-success">
                <strong>Success!</strong> The script database has been updated.
            </div>
        </div>

        <script>
            setTimeout(function() {
                window.location = '<?= $config->getUrl() ?>/register';
            }, 9000);
        </script>
<?php

        exit;
    } else {
        $cache->cacheFile(
            OPTIONS_FILE,
            str_replace(
                "'registered' => false",
                "'registered' => true",
                file_get_contents(OPTIONS_FILE)
            )
        );
    }

?>
    <link rel="stylesheet" href="<?= $config->getUrl() ?>/css/bootstrap.min.css" />

    <div class="container">
        <h2>Information</h2>

        <div class="alert alert-success">
            <strong>Success!</strong> The script database has been updated.
        </div>
    </div>

    <script>
        setTimeout(function() {
            window.location = '<?= $config->getUrl() ?>/log-in';
        }, 9000);
    </script>
<?php

    exit;
} elseif (
    !empty($optionData)
    && iv($options['version']) <= iv($optionData['option_version'])
) {
?>
    <link rel="stylesheet" href="<?= $config->getUrl() ?>/css/bootstrap.min.css" />

    <div class="container">
        <h2>Information</h2>

        <div class="alert alert-danger">
            <strong>Danger!</strong> The script is equal to or older than the database.
        </div>
    </div>
<?php

    exit;
}

$db->dbQuery("SET SQL_MODE = 'ALLOW_INVALID_DATES'");

$db->dbQuery("SET NAMES '" . $database['db_names'] . "'");

$db->dbQuery(
    'CREATE DATABASE IF NOT EXISTS `' . $database['db_database'] . '`
        DEFAULT CHARACTER SET ' . $database['db_names'] . '
        COLLATE ' . $database['db_collate']
);

$db->dbQuery('USE `' . $database['db_database'] . '`');

$db->dbQuery(
    "CREATE TABLE IF NOT EXISTS `option` (
        `option_version` VARCHAR(11) NOT NULL DEFAULT '"
            . $options['version'] . "',
        `option_installed` TINYINT NOT NULL DEFAULT 1,
        `option_registered` TINYINT NOT NULL DEFAULT 0
    ) ENGINE=InnoDB DEFAULT CHARSET=" . $database['db_names'] . ' COLLATE='
        . $database['db_collate']
);

$query = $rm->createQuery(
    'INSERT INTO `option` () VALUES ()'
)->getStrQuery();

$db->dbQuery($query);

$db->dbQuery(
    "CREATE TABLE IF NOT EXISTS `user` (
        `user_id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
        `user_admin` TINYINT NOT NULL DEFAULT 0,
        `user_active` TINYINT NOT NULL DEFAULT 1,
        `user_login` VARCHAR(20) NOT NULL DEFAULT '',
        `user_login_canonical` VARCHAR(20) NOT NULL DEFAULT '',
        `user_email` VARCHAR(100) NOT NULL DEFAULT '',
        `user_email_canonical` VARCHAR(100) NOT NULL DEFAULT '',
        `user_password` VARCHAR(255) NOT NULL DEFAULT '',
        `user_key` VARCHAR(255) NOT NULL DEFAULT '',
        `user_ip_added` VARCHAR(15) NOT NULL DEFAULT '',
        `user_date_added` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
        `user_ip_updated` VARCHAR(15) NOT NULL DEFAULT '',
        `user_date_updated` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
        `user_ip_loged` VARCHAR(15) NOT NULL DEFAULT '',
        `user_date_loged` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
        PRIMARY KEY (`user_id`),
        UNIQUE KEY `unique_login_canonical` (`user_login_canonical`),
        UNIQUE KEY `unique_email_canonical` (`user_email_canonical`),
    --  KEY `user_id` (`user_id`),
    --  KEY `user_admin` (`user_admin`),
    --  KEY `user_active` (`user_active`),
    --  KEY `user_login` (`user_login`),
    --  KEY `user_login_canonical` (`user_login_canonical`),
    --  KEY `user_email` (`user_email`),
    --  KEY `user_email_canonical` (`user_email_canonical`),
        KEY `user_password` (`user_password`)
    --  KEY `user_key` (`user_key`),
    --  KEY `user_ip_added` (`user_ip_added`),
    --  KEY `user_date_added` (`user_date_added`),
    --  KEY `user_ip_updated` (`user_ip_updated`),
    --  KEY `user_date_updated` (`user_date_updated`),
    --  KEY `user_ip_loged` (`user_ip_loged`),
    --  KEY `user_date_loged` (`user_date_loged`)
    ) ENGINE=InnoDB DEFAULT CHARSET=" . $database['db_names'] . ' COLLATE='
        . $database['db_collate']
);

$db->dbQuery(
    "CREATE TABLE IF NOT EXISTS `list` (
        `list_id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
        `list_name` VARCHAR(100) NOT NULL DEFAULT '',
        PRIMARY KEY (`list_id`),
    --  KEY `list_id` (`list_id`),
        KEY `list_name` (`list_name`)
    ) ENGINE=InnoDB DEFAULT CHARSET=" . $database['db_names'] . ' COLLATE='
        . $database['db_collate']
);

$db->dbQuery(
    "CREATE TABLE IF NOT EXISTS `email` (
        `email_id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
        `list_id` INT UNSIGNED NOT NULL DEFAULT 0,
        `email_name` VARCHAR(100) NOT NULL DEFAULT '',
        `email_email` VARCHAR(100) NOT NULL DEFAULT '',
        `email_email_canonical` VARCHAR(100) NOT NULL DEFAULT '',
        `email_ip_added` VARCHAR(15) NOT NULL DEFAULT '',
        `email_date_added` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
        PRIMARY KEY (`email_id`),
    --  KEY `email_id` (`email_id`),
    --  KEY `list_id` (`list_id`),
    --  KEY `email_name` (`email_name`),
    --  KEY `email_email` (`email_email`),
        KEY `email_email_canonical` (`email_email_canonical`)
    --  KEY `email_ip_added` (`email_ip_added`),
    --  KEY `email_date_added` (`email_date_added`)
    ) ENGINE=InnoDB DEFAULT CHARSET=" . $database['db_names'] . ' COLLATE='
        . $database['db_collate']
);

$db->dbQuery(
    "CREATE TABLE IF NOT EXISTS `text` (
        `text_id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
        `text_subject` VARCHAR(100) NOT NULL DEFAULT '',
        `text_message` TEXT NOT NULL,
        PRIMARY KEY (`text_id`),
    --  KEY `text_id` (`text_id`),
        KEY `text_subject` (`text_subject`)
    --  KEY `text_message` (`text_message`)
    ) ENGINE=InnoDB DEFAULT CHARSET=" . $database['db_names'] . ' COLLATE='
        . $database['db_collate']
);

$db->dbQuery(
    'CREATE TABLE IF NOT EXISTS `send` (
        `list_id` INT UNSIGNED NOT NULL DEFAULT 0,
        `email_id` INT UNSIGNED NOT NULL DEFAULT 0,
        `text_id` INT UNSIGNED NOT NULL DEFAULT 0,
        `send_count` INT UNSIGNED NOT NULL DEFAULT 0
    ) ENGINE=InnoDB DEFAULT CHARSET=' . $database['db_names'] . ' COLLATE='
        . $database['db_collate']
);

$query = $rm->createQuery(
    'INSERT INTO `send` () VALUES ()'
)->getStrQuery();

$db->dbQuery($query);

$db->dbQuery(
    'ALTER TABLE `email`
        ADD CONSTRAINT `email_ibfk_1` FOREIGN KEY (`list_id`) REFERENCES `list` (`list_id`)'
);

$cache->cacheFile(
    OPTIONS_FILE,
    str_replace(
        "'installed' => false",
        "'installed' => true",
        file_get_contents(OPTIONS_FILE)
    )
);

?>
<link rel="stylesheet" href="<?= $config->getUrl() ?>/css/bootstrap.min.css" />

<div class="container">
    <h2>Information</h2>

    <div class="alert alert-success">
        <strong>Success!</strong> The script database has been installed.
    </div>
</div>

<script>
    setTimeout(function() {
        window.location = '<?= $config->getUrl() ?>/register';
    }, 9000);
</script>
<?php

exit;

function iv(string $version): int
{
    $input = explode('.', $version);
    $string[0] = str_pad($input[0], 3, '0', STR_PAD_LEFT);
    $string[1] = str_pad($input[1], 3, '0', STR_PAD_LEFT);
    $string[2] = str_pad($input[2], 3, '0', STR_PAD_LEFT);

    return (int) ($string[0] . $string[1] . $string[2]);
}
