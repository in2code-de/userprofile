<?php
if (!defined('TYPO3_MODE')) {
    die('Access denied.');
}

/**
 * FE Plugin
 */
\TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin('In2code.' . $_EXTKEY, 'Pi1', 'Show profile for a Frontend User');
\TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin('In2code.' . $_EXTKEY, 'Pi2', 'List profile for Frontend Users');

/**
 * Static TypoScript
 */
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addStaticFile($_EXTKEY, 'Configuration/TypoScript',
    'Main settings for userprofile');

/**
 * Disable non needed fields in tt_content
 */
$GLOBALS['TCA']['tt_content']['types']['list']['subtypes_excludelist']['userprofile_pi1'] = 'select_key';
$GLOBALS['TCA']['tt_content']['types']['list']['subtypes_excludelist']['userprofile_pi2'] = 'select_key';
