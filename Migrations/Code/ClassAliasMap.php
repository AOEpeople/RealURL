<?php
return [
    'tx_realurl' => \AOE\Realurl\Realurl::class,
    'tx_realurl_cachemgmt' => \AOE\Realurl\Cachemgmt::class,
    'tx_realurl_configurationService' => \AOE\Realurl\Service\ConfigurationService::class,
    'tx_realurl_configurationService_exception' => \AOE\Realurl\Exception\ConfigurationServiceException::class,
    'tx_realurl_crawler' => \AOE\Realurl\Crawler::class,
    'tx_realurl_pagepath' => \AOE\Realurl\Pagepath::class,
    'tx_realurl_pathgenerator' => \AOE\Realurl\Pathgenerator::class,
    'tx_realurl_rootlineException' => \AOE\Realurl\Exception\RootlineException::class,
    'tx_realurl_tcemain' => \AOE\Realurl\Tcemain::class,
    'tx_realurl_modfunc1'=> \AOE\Realurl\modfunc1\Modfunc1::class

];
