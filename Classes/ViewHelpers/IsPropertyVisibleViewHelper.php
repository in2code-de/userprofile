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
     * @return bool
     */
    public function render(): bool
    {
        return $this->frontendUserService->showProperty(
            $this->arguments['user'],
            $this->arguments['propertyName']
        );
    }

    /**
     * Initialize
     *
     * @return void
     */
    public function initializeArguments()
    {
        parent::initializeArguments();
        $this->registerArgument('user', 'object', 'FrontendUser which should get displayed', true);
        $this->registerArgument('propertyName', 'string', 'Property of the FrontendUser which should be displayed', true);
    }
}
