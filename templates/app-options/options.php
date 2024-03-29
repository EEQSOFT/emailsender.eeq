<?php

echo "<?php

return
[
    'version' => '" . $this->config->getAppVersion() . "',
    'installed' => true,
    'registered' => true,
    'admin_email' => '" . $adminEmail . "',
    'emails_number' => " . $emailsNumber . ",
    'time_period' => " . $timePeriod . ",
    'unsubscribe_active' => " . (($unsubscribeActive) ? 'true' : 'false') . ",
    'unsubscribe_url' => '" . $unsubscribeUrl . "',
    'newsletter_name' => '" . $newsletterName . "'
];
";
