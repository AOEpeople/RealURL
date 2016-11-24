<?php
$EM_CONF[$_EXTKEY] = array(
    'title' => 'RealURL: speaking paths for TYPO3',
    'description' => 'Converts page ids and GET vars to speaking URL paths',
    'category' => 'fe',
    'state' => 'stable',
    'uploadfolder' => 0,
    'createDirs' => '',
    'clearCacheOnLoad' => 0,
    'author' => 'Dmitry Dulepov',
    'author_email' => 'dmitry.dulepov@gmail.com',
    'version' => '1.12.8.AOE',
    'constraints' => array(
        'depends' => array(
            'php' => '5.3.2-5.999.999',
            'typo3' => '4.5.0-6.2.999',
        ),
        'conflicts' => array(
            'cooluri' => '',
            'simulatestatic' => '',
        ),
        'suggests' => array(
            'static_info_tables' => '2.0.2-',
        ),
    )
);
