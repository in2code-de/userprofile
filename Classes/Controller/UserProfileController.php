<?php
declare(strict_types=1);

namespace In2code\Userprofile\Controller;

use In2code\Userprofile\Domain\Model\FrontendUser;
use In2code\Userprofile\Domain\Repository\FrontendUserRepository;
use TYPO3\CMS\Core\Messaging\AbstractMessage;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;

class UserProfileController extends ActionController
{
    /**
     * @var FrontendUserRepository
     */
    public $userProfileRepository;

    /**
     * @var persistenceManager
     */
    public $persistenceManager;

    public function injectUserProfileRepository(FrontendUserRepository $userProfileRepository)
    {
        $this->userProfileRepository = $userProfileRepository;
    }

    public function injectPersistenceManager(PersistenceManager $persistenceManager)
    {
        $this->persistenceManager = $persistenceManager;
    }

    /**
     * @ignoevalidation $userProfile
     * @param FrontendUser|null $userProfile
     */
    public function showAction(FrontendUser $userProfile = null)
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


        // is users own profile
        if ($this->frontendUserService->isOwnProfile($userProfile)) {
            $this->view->assign([
                'isOwnProfile' => true,
                'privacySettings' => $userProfile->getCompiledPrivacySettings(
                    $this->settings['privacy']
                )
            ]);
        }

        // display a profile of a user
        $this->view->assign('userProfile', $userProfile);

        // render an edit button, if the current user is logged in
        // TODO: Implement this functionality or remove this comment!
    }

    public function changeProfileVisibilityAction(FrontendUser $userProfile)
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

    public function privacyEditAction(FrontendUser $userProfile)
    {
        $this->view->assign('userProfile', $userProfile);
        $this->view->assign('privacySettings', $userProfile->getCompiledPrivacySettings($this->settings['privacy']));
    }

    public function privacyUpdateAction(FrontendUser $userProfile)
    {
        // process privacy settings
        $userProfile->compilePrivacySettings($this->request->getArgument('privacy'), $this->settings['privacy']);

        $this->userProfileRepository->update($userProfile);

        $this->addFlashMessage('Your privacy settings were updated', 'Success', AbstractMessage::OK);

        $this->redirect('show');
    }

    public function editAction(FrontendUser $userProfile)
    {
        $this->view->assign('userProfile', $userProfile);
    }

    public function updateAction(FrontendUser $userProfile)
    {
        // store changes in database
        $this->userProfileRepository->update($userProfile);

        // clear cache entry for this entry
        // TODO: Implement this functionality or remove this comment!
    }
}
