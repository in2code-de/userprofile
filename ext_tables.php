<?php
if (!defined('TYPO3_MODE')) {
    die('Access denied.');
}

call_user_func(function () {

    /**
     * FE Plugin
     */
    \TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin('userprofile', 'Pi1', 'User Profile');
});
