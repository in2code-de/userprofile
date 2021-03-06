<?php
declare(strict_types=1);

$EM_CONF[$_EXTKEY] = [
    'title' => 'User profile',
    'description' => 'Creates a profile page for TYPO3 frontend user based on Extbase and Fluid and on TYPO3 8.7',
    'category' => 'plugin',
    'author' => 'Stefan Busemann',
    'author_email' => 'stefan@in2code.de',
    'author_company' => '',
    'shy' => '',
    'priority' => '',
    'module' => '',
    'state' => 'beta',
    'internal' => '',
    'uploadfolder' => '0',
    'createDirs' => '',
    'modify_tables' => '',
    'clearCacheOnLoad' => 0,
    'lockType' => '',
    'version' => '0.3.0',
    'constraints' => [
        'depends' => [
            'typo3' => '8.7.0-9.5.99',
            'php' => '7.0.0-7.99.99',
        ],
        'conflicts' => [],
        'suggests' => [],
    ],
];
