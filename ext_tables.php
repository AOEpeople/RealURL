<?php
defined('TYPO3_MODE') or die();

if (TYPO3_MODE === 'BE') {
    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::insertModuleFunction(
        'web_info',
        'tx_realurl_modfunc1',
        null,
        'LLL:EXT:realurl/locallang_db.xml:moduleFunction.tx_realurl_modfunc1',
        'function',
        'online'
    );
}

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addLLrefForTCAdescr(
    'pages',
    'EXT:realurl/Resources/Private/Language/locallang_csh.xlf'
);

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addLLrefForTCAdescr(
    'pages_language_overlay',
    'EXT:realurl/Resources/Private/Language/locallang_csh.xlf'
);
