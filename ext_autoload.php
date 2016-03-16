<?php

$extensionPath = t3lib_extMgm::extPath('realurl');
return array(
	'tx_realurl' => $extensionPath . 'class.tx_realurl.php',
	'tx_realurl_cachemgmt' => $extensionPath . 'class.tx_realurl_cachemgmt.php',
	'tx_realurl_pagepath' => $extensionPath . 'class.tx_realurl_pagepath.php',
	'tx_realurl_pathgenerator' => $extensionPath . 'class.tx_realurl_pathgenerator.php',
	'tx_realurl_advanced' => $extensionPath . 'class.tx_realurl_advanced.php',
	'tx_realurl_autoconfgen' => $extensionPath . 'class.tx_realurl_autoconfgen.php',
	'tx_realurl_configurationservice' => $extensionPath . 'class.tx_realurl_configurationService.php',
	'tx_realurl_configurationservice_exception' => $extensionPath . 'class.tx_realurl_configurationService_exception.php',
	'tx_realurl_dummy' => $extensionPath . 'class.tx_realurl_dummy.php',
	'tx_realurl_tcemain' => $extensionPath . 'class.tx_realurl_tcemain.php',
	'tx_realurl_userfunctest' => $extensionPath . 'class.tx_realurl_userfunctest.php',
	'tx_realurl_cleanuphandler' => $extensionPath . 'cleanup/class.tx_realurl_cleanuphandler.php',
	'tx_realurl_module1' => $extensionPath . 'mod1/index.php',
	'tx_realurl_modfunc1' => $extensionPath . 'modfunc1/class.tx_realurl_modfunc1.php',
	'tx_realurl_pagebrowser' => $extensionPath . 'modfunc1/class.tx_realurl_pagebrowser.php',
	'tx_realurl_configurationservice_testcase' => $extensionPath . 'tests/class.tx_realurl_configurationService_testcase.php',
	'tx_realurl_abstractdatabase_testcase' => $extensionPath . 'tests/class.tx_realurl_abstractDatabase_testcase.php'
);
