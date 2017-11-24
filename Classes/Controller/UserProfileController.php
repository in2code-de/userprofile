<?php
declare(strict_types=1);

namespace T3o\Userprofile\Controller;

use T3o\Userprofile\Domain\Model\UserProfile;
use T3o\Userprofile\Domain\Repository\UserProfileRepository;
use TYPO3\CMS\Core\Messaging\AbstractMessage;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;

/**
 * Class UserController
 */
class UserProfileController extends ActionController
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
     * @param \T3o\Userprofile\Domain\Userprofile $userProfile
     *
     * @return void
     *
     * @ignoevalidation $userProfile
     */
    public function showAction(UserProfile $userProfile = NULL)
    {
        if (!$userProfile) {
            if ($GLOBALS['TSFE']->fe_user->user['uid']>0) {
                $userProfile = $this->userProfileRepository->findByUid($GLOBALS['TSFE']->fe_user->user['uid']);
                if (!$userProfile) {
                    $this->addFlashMessage('Something went wrong. We were not able to find your profile found!','Profile can not be shown',AbstractMessage::ERROR);
                }
                $this->view->assign('showEditButton',true);
            } else {
                $this->addFlashMessage('You need to be logged in!','Profile can not be shown',AbstractMessage::ERROR);
            }
        }
        // display a profile of a user
        $this->view->assign('userProfile',$userProfile);
        // render an edit button, if the current user is logged in
    }

    /**
     *
     */
    public function editAction()
    {

    }

    /**
     * @param \T3o\Userprofile\Domain\Userprofile $userProfile
     *
     * @return void
     *
     * @ignoevalidation $userProfile
     */
    public function updateAction(UserProfile $userProfile)
    {
        // store changes in database
        $this->userProfileRepository->update($userProfile);
        // clear cache entry for this entry
    }
}
