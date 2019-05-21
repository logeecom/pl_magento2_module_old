<?php

// copy this file as autoload.php and replace real path to the Magento installation root directory
require '/REAL-PATH-TO-MAGENTO-ROOT/app/bootstrap.php';

$bootstrap = \Magento\Framework\App\Bootstrap::create(BP, $_SERVER);
$bootstrap->createApplication(\Magento\Framework\App\Cron::class);

require __DIR__ . '/../../../vendor/autoload.php';
