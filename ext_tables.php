<?php
defined('TYPO3_MODE') or die('Access denied.');

/*
 * FE Plugin
 */
\TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin('Userprofile', 'Pi1', 'Show profile for a Frontend User');
\TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin('Userprofile', 'Pi2', 'List profile for Frontend Users');
