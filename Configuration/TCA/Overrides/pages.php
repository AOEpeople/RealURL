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
    'tx_realurl_nocache' => [
        'label' => 'LLL:EXT:realurl/locallang_db.xml:pages.tx_realurl_nocache',
        'exclude' => 1,
        'config' => [
            'type' => 'check',
            'items' => [
                ['', '']
            ]
        ]
    ]
];

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTCAcolumns(
    'pages',
    $additionalColumns
);

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addFieldsToPalette(
    'pages',
    'tx_realurl',
    'tx_realurl_pathsegment,--linebreak--,tx_realurl_pathoverride,tx_realurl_exclude,tx_realurl_nocache'
);

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addToAllTCAtypes(
    'pages',
    '--palette--;LLL:EXT:realurl/locallang_db.xml:pages.tx_realurl;tx_realurl',
    '1,2,5,4,199,254',
    'after:title'
);

$GLOBALS['TCA']['pages']['ctrl']['requestUpdate'] .= ',tx_realurl_exclude';
$GLOBALS['TCA']['pages']['ctrl']['shadowColumnsForNewPlaceholders'] .=
    ',tx_realurl_pathsegment,tx_realurl_exclude,tx_realurl_pathoverride,tx_realurl_nocache';
$GLOBALS['TCA']['pages']['palettes']['tx_realurl']['canNotCollapse'] = 1;
