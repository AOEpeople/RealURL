<?php
defined('TYPO3_MODE') or die();

if (TYPO3_MODE == 'FE') {
    //hook to force regeneration if crawler is active:
    $TYPO3_CONF_VARS['SC_OPTIONS']['tslib/class.tslib_fe.php']['insertPageIncache']['tx_realurl'] = 'EXT:realurl/class.tx_realurl_crawler.php:tx_realurl_crawler';
    $TYPO3_CONF_VARS['SC_OPTIONS']['tslib/class.tslib_fe.php']['headerNoCache']['tx_realurl'] = 'EXT:realurl/class.tx_realurl_crawler.php:tx_realurl_crawler->headerNoCache';
}

if (TYPO3_MODE == 'BE') {
    // Register processing instruction on tx_crawler
    $TYPO3_CONF_VARS['EXTCONF']['crawler']['procInstructions']['tx_realurl_rebuild'] = 'Force page link regeneration';
}

$TYPO3_CONF_VARS['SC_OPTIONS']['t3lib/class.t3lib_tstemplate.php']['linkData-PostProc']['tx_realurl'] =
    'EXT:realurl/class.tx_realurl.php:&tx_realurl->encodeSpURL';
$TYPO3_CONF_VARS['SC_OPTIONS']['tslib/class.tslib_content.php']['typoLink_PostProc']['tx_realurl'] =
    'EXT:realurl/class.tx_realurl.php:&tx_realurl->encodeSpURL_urlPrepend';
$TYPO3_CONF_VARS['SC_OPTIONS']['tslib/class.tslib_fe.php']['checkAlternativeIdMethods-PostProc']['tx_realurl'] =
    'EXT:realurl/class.tx_realurl.php:&tx_realurl->decodeSpURL';
$TYPO3_CONF_VARS['SC_OPTIONS']['t3lib/class.t3lib_tcemain.php']['clearPageCacheEval']['tx_realurl'] =
    'EXT:realurl/class.tx_realurl.php:&tx_realurl->clearPageCacheMgm';

$TYPO3_CONF_VARS['SC_OPTIONS']['t3lib/class.t3lib_tcemain.php']['clearAllCache_additionalTables']['tx_realurl_urldecodecache'] =
    'tx_realurl_urldecodecache';
$TYPO3_CONF_VARS['SC_OPTIONS']['t3lib/class.t3lib_tcemain.php']['clearAllCache_additionalTables']['tx_realurl_urlencodecache'] =
    'tx_realurl_urlencodecache';

$TYPO3_CONF_VARS['SC_OPTIONS']['t3lib/class.t3lib_tcemain.php']['processDatamapClass']['tx_realurl'] =
    'EXT:realurl/class.tx_realurl_tcemain.php:&tx_realurl_tcemain';
$TYPO3_CONF_VARS['SC_OPTIONS']['t3lib/class.t3lib_tcemain.php']['processCmdmapClass']['tx_realurl'] =
    'EXT:realurl/class.tx_realurl_tcemain.php:&tx_realurl_tcemain';

// register hook to add the excludemiddle field into the list of fields for new localization records
$TYPO3_CONF_VARS['SC_OPTIONS']['t3lib/class.t3lib_tcemain.php']['processDatamap_preProcessFieldArray']['tx_realurl'] = 'EXT:realurl/class.tx_realurl_processdatamap.php:&tx_realurl_processdatamap';

$TYPO3_CONF_VARS['FE']['addRootLineFields'] .= ',tx_realurl_pathsegment,tx_realurl_exclude,tx_realurl_pathoverride';

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

// TODO this can never be true after unsetting $_realurl_conf
if ($_realurl_conf['addpageOverlayFields'] !== 0) {
    $TYPO3_CONF_VARS['FE']['pageOverlayFields'] .= ',tx_realurl_pathsegment,tx_realurl_exclude,tx_realurl_pathoverride';
}
