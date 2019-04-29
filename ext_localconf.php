<?php
defined('TYPO3_MODE') or die();

/** @see \AOE\Realurl\Realurl::CACHE_DECODE */
const REAL_URL_CACHE_DECODE = 'realurl_decode';
/** @see \AOE\Realurl\Realurl::CACHE_ENCODE */
const REAL_URL_CACHE_ENCODE = 'realurl_encode';


if (TYPO3_MODE === 'FE') {
    //hook to force regeneration if crawler is active:
    $GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['tslib/class.tslib_fe.php']['insertPageIncache']['tx_realurl'] =
        \AOE\Realurl\Crawler::class;
    $GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['tslib/class.tslib_fe.php']['headerNoCache']['tx_realurl'] =
        \AOE\Realurl\Crawler::class . '->headerNoCache';
}

$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['t3lib/class.t3lib_tcemain.php']['clearPageCacheEval']['tx_realurl'] =
    \AOE\Realurl\Realurl::class . '->clearPageCacheMgm';
$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['t3lib/class.t3lib_tcemain.php']['processDatamapClass']['tx_realurl'] =
    \AOE\Realurl\Tcemain::class;
$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['t3lib/class.t3lib_tcemain.php']['processCmdmapClass']['tx_realurl'] =
    \AOE\Realurl\Tcemain::class;

$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['t3lib/class.t3lib_tstemplate.php']['linkData-PostProc']['tx_realurl'] =
    \AOE\Realurl\Realurl::class . '->encodeSpURL';

$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['tslib/class.tslib_content.php']['typoLink_PostProc']['tx_realurl'] =
    \AOE\Realurl\Realurl::class . '->encodeSpURL_urlPrepend';

$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['tslib/class.tslib_fe.php']['checkAlternativeIdMethods-PostProc']['tx_realurl'] =
    \AOE\Realurl\Realurl::class . '->decodeSpURL';

$GLOBALS['TYPO3_CONF_VARS']['FE']['addRootLineFields'] .= ',tx_realurl_pathsegment,tx_realurl_exclude,tx_realurl_pathoverride';
$GLOBALS['TYPO3_CONF_VARS']['FE']['pageOverlayFields'] .= ',tx_realurl_pathsegment,tx_realurl_exclude,tx_realurl_pathoverride';

if (!is_array($GLOBALS['TYPO3_CONF_VARS']['SYS']['caching']['cacheConfigurations'][REAL_URL_CACHE_DECODE])) {
    $GLOBALS['TYPO3_CONF_VARS']['SYS']['caching']['cacheConfigurations'][REAL_URL_CACHE_DECODE] = [
        'backend' => \TYPO3\CMS\Core\Cache\Backend\Typo3DatabaseBackend::class,
        'groups' => ['pages']
    ];
}
if (!is_array($GLOBALS['TYPO3_CONF_VARS']['SYS']['caching']['cacheConfigurations'][REAL_URL_CACHE_ENCODE])) {
    $GLOBALS['TYPO3_CONF_VARS']['SYS']['caching']['cacheConfigurations'][REAL_URL_CACHE_ENCODE] = [
        'backend' => \TYPO3\CMS\Core\Cache\Backend\Typo3DatabaseBackend::class,
        'groups' => ['pages']
    ];
}

// TYPO3 Log API configuration
$GLOBALS['TYPO3_CONF_VARS']['LOG']['tx']['realurl']['writerConfiguration'] = [
    \TYPO3\CMS\Core\Log\LogLevel::ERROR => [
        \TYPO3\CMS\Core\Log\Writer\PhpErrorLogWriter::class => []
    ]
];

// Include configuration file
$_realurl_conf = @unserialize($_EXTCONF);
if (is_array($_realurl_conf)) {
    $_realurl_conf_file = trim($_realurl_conf['configFile']);
    if ($_realurl_conf_file && @file_exists(PATH_site . $_realurl_conf_file)) {
        require_once(PATH_site . $_realurl_conf_file);
    }
    unset($_realurl_conf_file);
}

unset($_realurl_conf);
