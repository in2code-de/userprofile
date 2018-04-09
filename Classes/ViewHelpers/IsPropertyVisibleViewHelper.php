<?php
declare(strict_types=1);
namespace In2code\Userprofile\ViewHelpers;

use In2code\Userprofile\Domain\Service\FrontendUserService;
use In2code\Userprofile\Domain\Model\FrontendUser;
use TYPO3\CMS\Fluid\Core\ViewHelper\AbstractViewHelper;

class IsPropertyVisibleViewHelper extends AbstractViewHelper
{
    /**
     * @var FrontendUserService
     */
    protected $frontendUserService;

    /**
     * @param FrontendUserService $frontendUserService
     * @return void
     */
    public function injectFrontendUserService(FrontendUserService $frontendUserService)
    {
        $this->frontendUserService = $frontendUserService;
    }

    /**
     * Check if a property of the userprofile is visible in the current context
     * @param string $propertyName
     * @param FrontendUser $user
     * @return bool
     */
    public function render(string $propertyName, FrontendUser $user): bool
    {
        return $this->frontendUserService->showProperty(
            $user,
            $propertyName
        );
    }
}
