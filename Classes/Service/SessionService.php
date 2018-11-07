<?php
namespace In2code\Userprofile\Service;

use In2code\Userprofile\Domain\Model\FrontendUser;
use In2code\Userprofile\Domain\Repository\FrontendUserRepository;
use TYPO3\CMS\Frontend\Authentication\FrontendUserAuthentication;

class SessionService implements \TYPO3\CMS\Core\SingletonInterface
{
    /**
     * @var FrontendUserRepository
     */
    protected $frontendUserRepository;

    /**
     * @var FrontendUserAuthentication
     */
    protected $feUserAuthentication;

    /**
     * @var FrontendUser
     */
    protected $frontendUser;

    public function __construct(FrontendUserRepository $frontendUserRepository)
    {
        $this->frontendUserRepository = $frontendUserRepository;
        if ($GLOBALS['TSFE']->fe_user) {
            $this->feUserAuthentication = $GLOBALS['TSFE']->fe_user;
            $this->initializeUser();
        }
    }

    protected function initializeUser()
    {
        if ($this->isLoggedIn()) {
            $this->frontendUser = $this->frontendUserRepository->findByUid((int)$this->feUserAuthentication->user['uid']);
        }
    }

    public function isLoggedIn(): bool
    {
        return $GLOBALS['TSFE']->loginUser;
    }

    /**
     * @return FrontendUser|null
     */
    public function getFrontendUser()
    {
        return $this->frontendUser;
    }
}
