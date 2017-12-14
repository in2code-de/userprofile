<?php
declare(strict_types=1);

namespace In2code\Userprofile\Controller;

use In2code\Userprofile\Domain\Model\UserProfile;
use In2code\Userprofile\Domain\Repository\UserProfileRepository;
use TYPO3\CMS\Core\Messaging\AbstractMessage;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;
use TYPO3\CMS\Extbase\Persistence\Generic\PersistenceManager;

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
     * @var persistenceManager
     * */
    public $persistenceManager;

    /**
     * @param UserProfileRepository $userProfileRepository
     */
    public function injectUserProfileRepository(UserProfileRepository $userProfileRepository)
    {
        $this->userProfileRepository = $userProfileRepository;
    }

    /**
     * @param PersistenceManager $persistenceManager
     */
    public function injectPersistenceManager(PersistenceManager $persistenceManager)
    {
        $this->persistenceManager = $persistenceManager;
    }

    /**
     * @param \In2code\Userprofile\Domain\Model\Userprofile $userProfile
     *
     * @return void
     *
     * @ignoevalidation $userProfile
     */
    public function showAction(UserProfile $userProfile = null)
    {
        if (!$userProfile) {
            if ($GLOBALS['TSFE']->fe_user->user['uid'] > 0) {
                $userProfile = $this->userProfileRepository->findByUid($GLOBALS['TSFE']->fe_user->user['uid']);
                if (!$userProfile) {
                    $this->addFlashMessage('Something went wrong. We were not able to find your profile found!',
                        'Profile can not be shown', AbstractMessage::ERROR);
                }
                $this->view->assign('showEditButton', true);
            } else {
                $this->addFlashMessage('You need to be logged in!', 'Profile can not be shown', AbstractMessage::ERROR);
            }
        }
        // display a profile of a user
        $this->view->assign('userProfile', $userProfile);

        $this->view->assign('privacySettings', $userProfile->getCompiledPrivacySettings($this->settings['privacy']));
        // render an edit button, if the current user is logged in
    }

    /**
     * @param \In2code\Userprofile\Domain\Model\Userprofile $userProfile
     *
     * @return void
     */
    public function changeProfileVisibilityAction(UserProfile $userProfile)
    {
        if ($userProfile->isPublicProfile()) {
            $userProfile->setPublicProfile(false);
            $this->addFlashMessage('Your profile is now hidden.', 'Profile visibility', AbstractMessage::INFO);
        } else {
            $userProfile->setPublicProfile(true);
            $this->addFlashMessage('Your profile is now visible to the public.', 'Profile visibility',
                AbstractMessage::OK);
        }
        $this->userProfileRepository->update($userProfile);
    }

    /**
     * @param \In2code\Userprofile\Domain\Model\Userprofile $userProfile
     *
     * @return void
     */
    public function privacyEditAction(UserProfile $userProfile)
    {
        $this->view->assign('userProfile', $userProfile);
        $this->view->assign('privacySettings', $userProfile->getCompiledPrivacySettings($this->settings['privacy']));
    }

    /**
     * @param \In2code\Userprofile\Domain\Model\UserProfile $userProfile
     *
     * @return void
     */
    public function privacyUpdateAction(UserProfile $userProfile)
    {
        // process privacy settings
        $userProfile->compilePrivacySettings($this->request->getArgument('privacy'), $this->settings['privacy']);

        $this->userProfileRepository->update($userProfile);

        $this->addFlashMessage('Your privacy settings were updated', 'Success', AbstractMessage::OK);

        $this->redirect('show');
    }

    /**
     * @param \In2code\Userprofile\Domain\Model\UserProfile $userProfile
     *
     * @return void
     */
    public function editAction(UserProfile $userProfile)
    {
        $this->view->assign('userProfile', $userProfile);
    }

    /**
     * @param \In2code\Userprofile\Domain\Model\UserProfile $userProfile
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
