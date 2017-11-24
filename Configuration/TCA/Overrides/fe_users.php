<?php

/**
 * Table configuration fe_users
 */
$feUsersColumns = [
    'privacy_settings' => [
        'exclude' => 0,
        'label' => 'Privacy settings',
        'config' => [
            'type' => 'text',
        ]
    ],
    'about_me' => [
        'exclude' => 0,
        'label' => 'about me',
        'config' => [
            'type' => 'text',
        ]
    ],
    'public_profile' => [
        'exclude' => 0,
        'label' => 'show user profile',
        'config' => [
            'type' => 'check',
        ]
    ],
];

$fields = 'privacy_settings,about_me,';

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addToAllTCAtypes(
    'fe_users',
    'privacy_settings,about_me,public_profile',
    '',
    'after:name'
);
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTCAcolumns('fe_users', $feUsersColumns);
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addToAllTCAtypes(
    'fe_users',
    '--div--;LLL:EXT:femanager/Resources/Private/Language/locallang_db.xlf:fe_users.tab, ' . $fields
);
