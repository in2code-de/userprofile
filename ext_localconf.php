<?php
defined('TYPO3_MODE') or die('Access denied.');

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
    'In2code.' . $_EXTKEY,
    'Pi1',
    [
        'UserProfile' => 'show,edit,update,privacyUpdate,privacyEdit,changeProfileVisibility,list',
    ],
    [
        'UserProfile' => 'show,edit,update,privacyUpdate,privacyEdit,changeProfileVisibility',
    ]
);

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
    'In2code.' . $_EXTKEY,
    'Pi2',
    [
        'UserProfile' => 'list,show,edit,update',
    ],
    [
        'UserProfile' => 'show,edit,update',
    ]
);
