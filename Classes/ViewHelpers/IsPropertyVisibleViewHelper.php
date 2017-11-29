<?php
declare(strict_types=1);

namespace T3o\Userprofile\ViewHelpers;

use T3o\Userprofile\Domain\Model\UserProfile;
use TYPO3\CMS\Fluid\Core\ViewHelper\AbstractViewHelper;

/**
 * Class IsPropertyVisibleViewHelper
 */
class IsPropertyVisibleViewHelper extends AbstractViewHelper
{
    /**
     * Check if a property of the userprofile is visible in the current context
     *
     * @param string $propertyName
     * @param UserProfile $userProfile
     * @return bool
     */
    public function render(string $propertyName, UserProfile $userProfile)
    {
        if ($GLOBALS['TSFE']->fe_user->user['uid']==$userProfile->getUid()) {
            #return true;
        }
        return $userProfile->showProperty($propertyName);
    }
}
