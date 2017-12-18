<?php
declare(strict_types=1);

namespace In2code\Userprofile\Domain\Model;

class FrontendUser extends \TYPO3\CMS\Extbase\Domain\Model\FrontendUser
{
    /**
     * @var string
     */
    public $privacySettings = '';

    /**
     * @var string
     */
    public $aboutMe = '';

    /**
     * @var bool
     */
    public $publicProfile = false;

    public function getPrivacySettings(): array
    {
        if (!$this->privacySettings) {
            $this->privacySettings = json_encode([]);
        }
        return json_decode($this->privacySettings, true);
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

    /**
     * @param string $propertyName
     * @return bool
     * @deprecated
     */
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

            // TODO: Implement this functionality or remove this comment!
            //if ($user == true) {
            //} else {
            //}
        }
        return false;
    }
}
