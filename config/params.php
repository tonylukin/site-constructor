<?php

return [
    'adminEmail' => 'admin@example.com',
    'senderEmail' => 'noreply@example.com',
    'senderName' => 'Example.com mailer',
    'adsenseSites' => (@include '_adsenseSites.php') ?: [],
    'googleAnalyticsCodes' => (@include '_googleAnalyticsCodes.php') ?: [],
];
