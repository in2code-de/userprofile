<?php
declare(strict_types=1);

namespace In2code\Userprofile\Controller;

use In2code\Userprofile\Domain\Repository\UserProfileRepository;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;

/**
 * Class ListUserProfileController
 */
class UserProfileListController extends ActionController
{
    /**
     * @var UserProfileRepository
     * */
    public $userProfileRepository;

    /**
     * @param UserProfileRepository $userProfileRepository
     */
    public function injectUserProfileRepository(UserProfileRepository $userProfileRepository)
    {
        $this->userProfileRepository = $userProfileRepository;
    }

    /**
     *
     */
    public function listAction()
    {
        $allUsers = $this->userProfileRepository->findAll();
        $this->view->assign('frontendUsers', $allUsers);
    }
}
