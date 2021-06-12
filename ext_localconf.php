<?php

defined('TYPO3_MODE') or die('Access denied.');

call_user_func(
    function () {
        \TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
            'userprofile',
            'Pi1',
            [
                \In2code\Userprofile\Controller\UserProfileController::class => 'show,edit,update,privacyUpdate,privacyEdit,changeProfileVisibility,list',
            ],
            [
                \In2code\Userprofile\Controller\UserProfileController::class => 'show,edit,update,privacyUpdate,privacyEdit,changeProfileVisibility',
            ]
        );

        \TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
            'userprofile',
            'Pi2',
            [
                \In2code\Userprofile\Controller\UserProfileController::class => 'list,show,edit,update',
            ],
            [
                \In2code\Userprofile\Controller\UserProfileController::class => 'show,edit,update',
            ]
        );
    }
);
