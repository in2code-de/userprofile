<?php

declare(strict_types=1);

$EM_CONF[$_EXTKEY] = [
    'title' => 'User profile',
    'description' => 'Creates a profile page for TYPO3 frontend user based on Extbase and Fluid and allows frontend users to maintain it',
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
    'version' => '1.0.0',
    'constraints' => [
        'depends' => [
            'typo3' => '10.4.0-10.4.99',
            'php' => '7.2.0-7.99.99',
        ],
        'conflicts' => [],
        'suggests' => [],
    ],
];
