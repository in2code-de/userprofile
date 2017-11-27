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
     * @return string
     */
    public function getPrivacySettings()
    {
        return json_decode($this->privacySettings);
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
                        $compiledPrivacySettings[$defaultPrivacySetting][$defaultSettingTemplateKey] = intval($currentPrivacySettings[$defaultPrivacySetting][$defaultSettingTemplateKey]);
                    }
                }
            }
        }

        return $compiledPrivacySettings;
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
