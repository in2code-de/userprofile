<?php
namespace In2code\Userprofile\Domain;

use In2code\Userprofile\Domain\Model\FrontendUser;
use In2code\Userprofile\Property\PrivacySettings;
use In2code\Userprofile\Service\SessionService;
use TYPO3\CMS\Core\SingletonInterface;

class FrontendUserService implements SingletonInterface
{
    /**
     * @var SessionService
     */
    protected $sessionService;

    public function __construct(SessionService $sessionService)
    {
        $this->sessionService = $sessionService;
    }

    public function showProperty(FrontendUser $user, string $propertyName)
    {
        $privacySettings = $this->getSettingsForProperty($user, $propertyName);

        // is own profile
        if ($this->isOwnProfile($user)) {
            return true;
        }

        // public
        if ($privacySettings->getPublic()) {
            return true;
        }

        // user is not logged in
        if (!$this->sessionService->isLoggedIn()) {
            return false;
        }

        // show only for logged in users
        if ($privacySettings->getAuthenticated()) {
            return true;
        }

        // show only in same groups
        if ($privacySettings->getGroup()
            && $this->checkGroupAccess($user)
        ) {
            return true;
        }

        return false;
    }

    /**
     * @param FrontendUser $user
     * @param string $propertyName
     * @return PrivacySettings
     */
    protected function getSettingsForProperty(FrontendUser $user, string $propertyName)
    {
        // get privacy settings for this property
        $privacySettingsArray = $user->getPrivacySettings();

        return PrivacySettings::createFromArray(
            $propertyName,
            $privacySettingsArray[$propertyName]
        );
    }

    /**
     * @param FrontendUser $user
     * @return bool
     */
    protected function checkGroupAccess(FrontendUser $user): bool
    {
        $currentUser = $this->sessionService->getFrontendUser();
        if (!$currentUser) {
            return false;
        }

        $accessibleUsergroups = $user->getUsergroup();
        foreach ($currentUser->getUsergroup() as $usergroup) {
            if ($accessibleUsergroups->contains($usergroup)) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param FrontendUser $user
     * @return bool
     */
    public function isOwnProfile(FrontendUser $user): bool
    {
        $currentUser = $this->sessionService->getFrontendUser();
        if (!$currentUser) {
            return false;
        }
        return ($user->getUid() === $currentUser->getUid());
    }

    /**
     * example data
     *  public => '0' (1 chars)
     *  authenticated => '0' (1 chars)
     *  groups => '0' (1 chars)
     *
     * @param FrontendUser $user
     * @param array $privacySettings
     * @return array
     */
    public function getCompiledPrivacySettings(
        FrontendUser $user,
        array $privacySettings = []
    ): array {
        $defaultPrivacySettings = $privacySettings['_default'] ?: [];
        $currentPrivacySettings = $user->getPrivacySettings();

        $compiledPrivacySettings = [];
        foreach ($privacySettings as $privacySettingName => $defaultPrivacySettingValue) {
            if ($privacySettingName !== '_default'
                && ($defaultPrivacySettingValue == 1
                    || is_array($defaultPrivacySettingValue)
                )
            ) {
                foreach ($defaultPrivacySettings as $defaultSettingTemplateKey => $defaultSettingTemplateValue) {
                    // set the default value of the TS setting
                    $compiledPrivacySettings[$privacySettingName][$defaultSettingTemplateKey] = (bool) $defaultSettingTemplateValue;

                    // check if there is an existing value
                    if (isset($currentPrivacySettings[$privacySettingName][$defaultSettingTemplateKey])) {
                        $compiledPrivacySettings[$privacySettingName][$defaultSettingTemplateKey] = (bool) $currentPrivacySettings[$privacySettingName][$defaultSettingTemplateKey];
                    }
                }
            }
        }

        return $compiledPrivacySettings;
    }

    /**
     * example data
     *  public => '0' (1 chars)
     *  authenticated => '0' (1 chars)
     *  groups => '0' (1 chars)
     *
     * @param FrontendUser $user
     * @param array $newPrivacySettings
     * @param array $defaultPrivacySettings
     * @return bool
     */
    public function compilePrivacySettings(
        FrontendUser $user,
        array $newPrivacySettings = [],
        array $defaultPrivacySettings = []
    ): bool {
        $defaultSettingsTemplate = $defaultPrivacySettings['_default'];
        $compiledPrivacySettings = [];

        foreach ($defaultPrivacySettings as $defaultPrivacySetting => $defaultPrivacySettingValue) {
            if ($defaultPrivacySetting <> '_default' and $defaultPrivacySettingValue == 1) {
                foreach ($defaultSettingsTemplate as $defaultSettingTemplateKey => $defaultSettingTemplateValue) {

                    // set the default value of the TS setting
                    $compiledPrivacySettings[$defaultPrivacySetting][$defaultSettingTemplateKey] = 0;

                    // check if there is an existing value
                    if ($newPrivacySettings[$defaultPrivacySetting][$defaultSettingTemplateKey]) {
                        $compiledPrivacySettings[$defaultPrivacySetting][$defaultSettingTemplateKey] = 1;
                    }
                }
            }
        }

        $user->setPrivacySettings($compiledPrivacySettings);

        return true;
    }
}
