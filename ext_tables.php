<?php
defined('TYPO3_MODE') or die('Access denied.');

/*
 * FE Plugin
 */
\TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin('In2code.' . $_EXTKEY, 'Pi1', 'Show profile for a Frontend User');
\TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin('In2code.' . $_EXTKEY, 'Pi2', 'List profile for Frontend Users');
