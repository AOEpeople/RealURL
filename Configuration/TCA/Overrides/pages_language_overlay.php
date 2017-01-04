<?php
defined('TYPO3_MODE') or die();

$additionalColumns = [
    'tx_realurl_pathsegment' => [
        'label' => 'LLL:EXT:realurl/locallang_db.xml:pages.tx_realurl_pathsegment',
        'exclude' => 1,
        'config' => [
            'type' => 'input',
            'size' => 50,
            'max' => 255,
            'eval' => 'trim,nospace,lower'
        ],
    ],
    'tx_realurl_pathoverride' => [
        'label' => 'LLL:EXT:realurl/locallang_db.xml:pages.tx_realurl_path_override',
        'exclude' => 1,
        'config' => [
            'type' => 'check',
            'items' => [
                ['', '']
            ]
        ]
    ],
    'tx_realurl_exclude' => [
        'label' => 'LLL:EXT:realurl/locallang_db.xml:pages.tx_realurl_exclude',
        'exclude' => 1,
        'config' => [
            'type' => 'check',
            'items' => [
                ['', '']
            ]
        ]
    ],
];

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTCAcolumns(
    'pages_language_overlay',
    $additionalColumns
);

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addFieldsToPalette(
    'pages_language_overlay',
    'tx_realurl',
    'tx_realurl_pathsegment, --linebreak--, tx_realurl_pathoverride, tx_realurl_exclude'
);

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addToAllTCAtypes(
    'pages_language_overlay',
    '--palette--;LLL:EXT:realurl/locallang_db.xml:pages.tx_realurl;tx_realurl',
    '',
    'after:title'
);

$GLOBALS['TCA']['pages_language_overlay']['palettes']['tx_realurl']['canNotCollapse'] = 1;
