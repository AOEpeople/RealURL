<?php
$EM_CONF[$_EXTKEY] = [
    'title' => 'RealURL: speaking paths for TYPO3',
    'description' => 'Converts page ids and GET vars to speaking URL paths',
    'category' => 'fe',
    'state' => 'stable',
    'uploadfolder' => 0,
    'createDirs' => '',
    'clearCacheOnLoad' => 0,
    'author' => 'Dmitry Dulepov',
    'author_email' => 'dmitry.dulepov@gmail.com',
    'version' => '1.12.8.13.AOE',
    'constraints' => [
        'depends' => [
            'php' => '7.0.0-7.99.99',
            'typo3' => '8.7.0-8.7.99',
        ],
        'conflicts' => [
            'cooluri' => '',
            'simulatestatic' => '',
        ],
        'suggests' => [
            'static_info_tables' => '2.0.2-',
        ],
    ]
];
