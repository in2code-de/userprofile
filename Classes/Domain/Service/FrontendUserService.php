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
}
