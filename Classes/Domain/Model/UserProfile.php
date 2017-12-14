<?php
declare(strict_types=1);

namespace In2code\Userprofile\Domain\Model;

use In2code\Femanager\Domain\Model\User;

class UserProfile extends User
{
    /**
     * @var string
     */
    public $privacySettings;

    /**
     * @var string
     */
    public $aboutMe;

    /**
     * @var bool
     */
    public $publicProfile = false;

    public function getPrivacySettings(): array
    {
        return json_decode($this->privacySettings, true);
    }

    public function getCompiledPrivacySettings(array $defaultPrivacySettings = []): array
    {
        /*
         * example data
         * public => '0' (1 chars)
         * authenticated => '0' (1 chars)
         * groups => '0' (1 chars)
         */
        $defaultSettingsTemplate = $defaultPrivacySettings['_default'];

        $currentPrivacySettings = $this->getPrivacySettings();

        foreach ($defaultPrivacySettings as $defaultPrivacySetting => $defaultPrivacySettingValue) {
            if ($defaultPrivacySetting <> '_default' and $defaultPrivacySettingValue == 1) {
                foreach ($defaultSettingsTemplate as $defaultSettingTemplateKey => $defaultSettingTemplateValue) {

                    // set the default value of the TS setting
                    $compiledPrivacySettings[$defaultPrivacySetting][$defaultSettingTemplateKey] = $defaultSettingTemplateValue;

                    // check if there is an existing value
                    if ($currentPrivacySettings[$defaultPrivacySetting][$defaultSettingTemplateKey]) {
                        $compiledPrivacySettings[$defaultPrivacySetting][$defaultSettingTemplateKey] = (int)$currentPrivacySettings[$defaultPrivacySetting][$defaultSettingTemplateKey];
                    }
                }
            }
        }

        return $compiledPrivacySettings;
    }

    public function compilePrivacySettings(array $newPrivacySettings = [], array $defaultPrivacySettings = []): bool
    {
        /*
         * example data
         * public => '0' (1 chars)
         * authenticated => '0' (1 chars)
         * groups => '0' (1 chars)
         */
        $defaultSettingsTemplate = $defaultPrivacySettings['_default'];

        foreach ($defaultPrivacySettings as $defaultPrivacySetting => $defaultPrivacySettingValue) {
            if ($defaultPrivacySetting <> '_default' and $defaultPrivacySettingValue == 1) {
                foreach ($defaultSettingsTemplate as $defaultSettingTemplateKey => $defaultSettingTemplateValue) {

                    // set the default value of the TS setting
                    $compiledPrivacySettings[$defaultPrivacySetting][$defaultSettingTemplateKey] = 0;

                    // check if there is an existing value
                    if ($newPrivacySettings[$defaultPrivacySetting . '.' . $defaultSettingTemplateKey]) {
                        $compiledPrivacySettings[$defaultPrivacySetting][$defaultSettingTemplateKey] = 1;
                    }
                }
            }
        }
        $this->setPrivacySettings($compiledPrivacySettings);

        return true;
    }

    public function setPrivacySettings(array $privacySettings)
    {
        $this->privacySettings = json_encode($privacySettings);
    }

    public function getAboutMe(): string
    {
        return $this->aboutMe;
    }

    public function setAboutme(string $aboutMe)
    {
        $this->aboutMe = $aboutMe;
    }

    public function isPublicProfile(): bool
    {
        return $this->publicProfile;
    }

    public function setPublicProfile(bool $publicProfile = false)
    {
        $this->publicProfile = $publicProfile;
    }

    public function showProperty(string $propertyName = '')
    {
        // get privacy settings for this property
        $privacySettingsArray = $this->getPrivacySettings();

        if (is_array($privacySettingsArray[$propertyName])) {
            $public = (int)$privacySettingsArray[$propertyName]['public'];
            $authenticated = (int)$privacySettingsArray[$propertyName]['authenticated'];
            $groups = (int)$privacySettingsArray[$propertyName]['groups'];

            // get context
            // User is logged in?
            if ($GLOBALS['TSFE']->fe_user->user['uid'] > 0) {
                // groups context
                if ($groups) {
                    // check if usergroups match
                    $useroroups = $this->getUsergroup();

                    // @todo exclude groups from comparision, if they are added for all users
                    foreach ($useroroups as $userqroup) {
                        $userGroupArray[] =  $userqroup->uid;
                    }
                    $feUserGroups  = explode(',', $GLOBALS['TSFE']->fe_user->user['usergroup']);

                    foreach ($feUserGroups as $feUserGroup) {
                        if (in_array($feUserGroup, $userGroupArray)) {
                            return true;
                        }
                    }
                }

                // authenticated context
                if ($authenticated) {
                    return true;
                }
            } else {
                // we are in public context
                if ($public) {
                    return true;
                } else {
                    return false;
                }
            }

            //if ($user == true) {

           // } else {

            //}
        }
        return false;
    }
}
