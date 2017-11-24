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
    public $publicProfile=false;

    /**
     * @return string
     */
    public function getPrivacySettings()
    {
        return $this->privacySettings;
    }

    /**
     * @param $defaultPrivacySettings
     * @return array
     */
    public function getCompilledPrivacySettings($defaultPrivacySettings)
    {
        #$defaultSettings = $defaultPrivacySettings['_default'];
        ##$this->getPrivacySettings();
        #return $this->privacySettings;
    }

    /**
     * @param string $privacySettings
     */
    public function setPrivacySettings($privacySettings)
    {
        $this->privacySettings = $privacySettings;
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
    public function setPublicProfile(bool $publicProfile=false)
    {
        $this->publicProfile = $publicProfile;
    }


}
