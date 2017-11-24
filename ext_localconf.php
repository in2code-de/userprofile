<?php
if (!defined('TYPO3_MODE')) {
    die('Access denied.');
}

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
    'T3o.' . $_EXTKEY,
    'Pi1',
    [
        'UserProfile' => 'show, edit, update',
        'UserProfileList' => 'list',
    ],
    [
        'UserProfile' => 'edit, update',
        'UserProfileList' => '',
    ]
);

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
    'T3o.' . $_EXTKEY,
    'Pi2',
    [
        'UserProfileList' => 'list',
        'UserProfile' => 'show, edit, update',
    ],
    [
        'UserProfileList' => '',
        'UserProfile' => 'show, edit, update',
    ]
);

