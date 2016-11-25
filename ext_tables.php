<?php
defined('TYPO3_MODE') or die();

if (TYPO3_MODE == 'BE') {
    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::insertModuleFunction(
        'web_info',
        'tx_realurl_modfunc1',
        null,
        'LLL:EXT:realurl/locallang_db.xml:moduleFunction.tx_realurl_modfunc1',
        'function',
        'online'
    );
}

$GLOBALS['TCA']['pages']['columns'] += array(
    'tx_realurl_pathsegment' => array(
        'label' => 'LLL:EXT:realurl/locallang_db.xml:pages.tx_realurl_pathsegment',
//        'displayCond' => 'FIELD:tx_realurl_exclude:!=:1',
        'exclude' => 1,
        'config' => array(
            'type' => 'input',
            'max' => 255,
            'eval' => 'trim,nospace,lower'
        ),
    ),
    'tx_realurl_pathoverride' => array(
        'label' => 'LLL:EXT:realurl/locallang_db.xml:pages.tx_realurl_path_override',
        'exclude' => 1,
        'config' => array(
            'type' => 'check',
            'items' => array(
                array('', '')
            )
        )
    ),
    'tx_realurl_exclude' => array(
        'label' => 'LLL:EXT:realurl/locallang_db.xml:pages.tx_realurl_exclude',
        'exclude' => 1,
        'config' => array(
            'type' => 'check',
            'items' => array(
                array('', '')
            )
        )
    ),
    'tx_realurl_nocache' => array(
        'label' => 'LLL:EXT:realurl/locallang_db.xml:pages.tx_realurl_nocache',
        'exclude' => 1,
        'config' => array(
            'type' => 'check',
            'items' => array(
                array('', ''),
            ),
        ),
    )
);

$GLOBALS['TCA']['pages']['ctrl']['requestUpdate'] .= ',tx_realurl_exclude';
$GLOBALS['TCA']['pages']['ctrl']['shadowColumnsForNewPlaceholders'] .=
    ',tx_realurl_pathsegment,tx_realurl_exclude,tx_realurl_pathoverride,tx_realurl_nocache';
$GLOBALS['TCA']['pages']['palettes']['137'] = array(
    'showitem' => 'tx_realurl_pathoverride'
);

// Put it for standard page
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addToAllTCAtypes(
    'pages',
    'tx_realurl_pathsegment;;137;;,tx_realurl_exclude',
    '2',
    'after:nav_title'
);
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addToAllTCAtypes(
    'pages',
    'tx_realurl_pathsegment;;137;;,tx_realurl_exclude',
    '1,5,4,199,254',
    'after:title'
);

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addLLrefForTCAdescr('pages', 'EXT:realurl/locallang_csh.xml');

$GLOBALS['TCA']['pages_language_overlay']['columns'] += array(
    'tx_realurl_pathsegment' => array(
        'label' => 'LLL:EXT:realurl/locallang_db.xml:pages.tx_realurl_pathsegment',
        'exclude' => 1,
        'config' => array(
            'type' => 'input',
            'max' => 255,
            'eval' => 'trim,nospace,lower'
        ),
    ),
    'tx_realurl_pathoverride' => array(
        'label' => 'LLL:EXT:realurl/locallang_db.xml:pages.tx_realurl_path_override',
        'exclude' => 1,
        'config' => array(
            'type' => 'check',
            'items' => array(
                array('', '')
            )
        )
    ),
    'tx_realurl_exclude' => array(
        'label' => 'LLL:EXT:realurl/locallang_db.xml:pages.tx_realurl_exclude',
        'exclude' => 1,
        'config' => array(
            'type' => 'check',
            'items' => array(
                array('', '')
            )
        )
    ),
);

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addToAllTCAtypes('pages_language_overlay',
    'tx_realurl_pathsegment,tx_realurl_pathoverride,tx_realurl_exclude', '', 'after:nav_title');
