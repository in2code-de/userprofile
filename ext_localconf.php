<?php
if (!defined('TYPO3_MODE')) {
    die('Access denied.');
}

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
    'T3o.' . $_EXTKEY,
    'Pi1',
    [
        'UserProfile' => 'show, edit, update, privacyUpdate, privacyEdit, changeProfileVisibility',
        'UserProfileList' => 'list',
    ],
    [
        'UserProfile' => 'edit, update, privacyUpdate, privacyEdit, changeProfileVisibility',
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

