<?php

declare(strict_types=1);

require(__DIR__ . '/../config/config.php');
require(__DIR__ . '/../src/autoload.php');

use App\Bundle\Cron;
use App\Core\{Database, Email, Manager};
use App\Repository\{EmailRepository, SendRepository, TextRepository};

$db = new Database();
$rm = new Manager($db);
$cron = new Cron();
$mail = new Email();

$options = require(OPTIONS_FILE);

$db->dbConnect();

$er = $rm->getRepository(EmailRepository::class);
$tr = $rm->getRepository(TextRepository::class);
$sr = $rm->getRepository(SendRepository::class);

$sendData = $sr->getSendData();
$textData = $tr->getTextData($sendData['text_id'] ?? 0);
$cronjobEmailList = $er->getCronjobEmailList(
    $sendData['list_id'] ?? 0,
    $sendData['email_id'] ?? 0,
    $options['emails_number']
);

if (!empty($cronjobEmailList) && !empty($textData) && !empty($sendData)) {
    foreach ($cronjobEmailList as $key => $value) {
        $sr->setSendEmail($key);

        $mail->sendEmail(
            gethostname(),
            $options['admin_email'],
            $value['email_email'],
            $textData['text_subject'],
            $textData['text_message']
                . (
                    ($options['unsubscribe_active']) ? "\n\n"
                        . 'Unsubscribe: ' . $options['unsubscribe_url']
                        . '/unsubscribe,' . $key . ','
                        . md5(
                            $key . $value['email_name']
                                . $value['email_email']
                                . $value['email_ip_added']
                                . $value['email_date_added']
                        ) : ''
                )
        );
    }

    $cron->appendCronjob(
        date('i H', time() + $options['time_period'] * 60)
            . ' * * * php ' . CRONJOB_FILE
    );
} else {
    $sr->setSendData(0, 0, 0, 0);

    $cron->removeCrontab();
}
