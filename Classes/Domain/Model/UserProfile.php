<?php
declare(strict_types=1);

namespace T3o\Userprofile\Domain\Model;

use In2code\Femanager\Domain\Model\User;

/**
 * Class UserProfile
 */
class UserProfile extends User
{

    /**
     * $privacySettings
     *
     * @var string
     */
    public $privacySettings;

    /**
     * $realName
     *
     * @var string
     */
    public $aboutMe;

    /**
     * $publicProfile
     *
     * @var boolean
     */
    public $publicProfile = false;

    /**
     * @return array
     */
    public function getPrivacySettings()
    {
        return json_decode($this->privacySettings, true);
    }

    /**
     * @param array $defaultPrivacySettings
     * @return array
     */
    public function getCompiledPrivacySettings($defaultPrivacySettings = array())
    {
        // example data
        // public => '0' (1 chars)
        // authenticated => '0' (1 chars)
        // groups => '0' (1 chars)
        $defaultSettingsTemplate = $defaultPrivacySettings['_default'];

        $currentPrivacySettings = $this->getPrivacySettings();

        foreach ($defaultPrivacySettings as $defaultPrivacySetting => $defaultPrivacySettingValue) {

            if ($defaultPrivacySetting <> '_default' AND $defaultPrivacySettingValue == 1) {
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


    /**
     * @param array $newPrivacySettings
     * @param array $defaultPrivacySettings
     * @return boolean
     */
    public function compilePrivacySettings($newPrivacySettings = array(), $defaultPrivacySettings = array())
    {
        // example data
        // public => '0' (1 chars)
        // authenticated => '0' (1 chars)
        // groups => '0' (1 chars)
        $defaultSettingsTemplate = $defaultPrivacySettings['_default'];

        foreach ($defaultPrivacySettings as $defaultPrivacySetting => $defaultPrivacySettingValue) {

            if ($defaultPrivacySetting <> '_default' AND $defaultPrivacySettingValue == 1) {
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
    /**
     * @param array $privacySettings
     */
    public function setPrivacySettings($privacySettings)
    {
        $this->privacySettings = json_encode($privacySettings);
    }

    /**
     * @return string
     */
    public function getAboutMe()
    {
        return $this->aboutMe;
    }

    /**
     * @param string $aboutMe
     */
    public function setAboutme($aboutMe)
    {
        $this->aboutMe = $aboutMe;
    }

    /**
     * @return bool
     */
    public function isPublicProfile(): bool
    {
        return $this->publicProfile;
    }

    /**
     * @param bool $publicProfile
     */
    public function setPublicProfile(bool $publicProfile = false)
    {
        $this->publicProfile = $publicProfile;
    }


}
