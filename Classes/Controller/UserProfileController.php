<?php
declare(strict_types=1);
namespace In2code\Userprofile\Controller;

use In2code\Userprofile\Domain\FrontendUserService;
use In2code\Userprofile\Domain\Model\FrontendUser;
use In2code\Userprofile\Domain\Repository\FrontendUserRepository;
use In2code\Userprofile\Service\SessionService;
use TYPO3\CMS\Core\Messaging\AbstractMessage;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;

class UserProfileController extends ActionController
{
    /**
     * @var FrontendUserRepository
     */
    protected $frontendUserRepository;

    /**
     * @var FrontendUserService
     */
    protected $frontendUserService;

    /**
     * @var SessionService
     */
    protected $sessionService;

    public function injectUserProfileRepository(FrontendUserRepository $frontendUserRepository)
    {
        $this->frontendUserRepository = $frontendUserRepository;
    }

    public function injectFrontendUserService(FrontendUserService $frontendUserService)
    {
        $this->frontendUserService = $frontendUserService;
    }

    public function injectSessionService(SessionService $sessionService)
    {
        $this->sessionService = $sessionService;
    }

    public function listAction()
    {
        $allUsers = $this->frontendUserRepository->findAll();
        $this->view->assign('frontendUsers', $allUsers);
    }

    /**
     * @param FrontendUser|null $user
     */
    public function showAction(FrontendUser $user = null)
    {
        if ($user === null) {
            $user = $this->sessionService->getFrontendUser();
            if ($user === null) {
                $this->addFlashMessage(
                    'Something went wrong. We were not able to find your profile found!',
                    'Profile can not be shown',
                    AbstractMessage::ERROR
                );
            }
        }

        // is users own profile
        if ($this->frontendUserService->isOwnProfile($user)) {
            $this->view->assignMultiple([
                'isOwnProfile' => true,
                'privacySettings' => $this->frontendUserService->getCompiledPrivacySettings(
                    $user,
                    $this->settings['privacy']
                ),
//                'privacySettings' => $user->getCompiledPrivacySettings(
//                    $this->settings['privacy']
//                )
            ]);
        } else if (!$user->isPublicProfile()) {
            $this->addFlashMessage(
                'You need to be logged in!',
                'Profile can not be shown',
                AbstractMessage::ERROR
            );
        }

        // display a profile of a user
        $this->view->assign('user', $user);
    }

    public function changeProfileVisibilityAction(FrontendUser $user)
    {
        if ($user->isPublicProfile()) {
            $user->setPublicProfile(false);
            $this->addFlashMessage(
                'Your profile is now hidden.',
                'Profile visibility',
                AbstractMessage::INFO
            );
        } else {
            $user->setPublicProfile(true);
            $this->addFlashMessage(
                'Your profile is now visible to the public.',
                'Profile visibility',
                AbstractMessage::OK
            );
        }
        $this->frontendUserRepository->update($user);
    }

    public function privacyEditAction(FrontendUser $user)
    {
        $this->view->assignMultiple([
            'user' => $user,
            'privacySettings' => $this->frontendUserService->getCompiledPrivacySettings(
                $user,
                $this->settings['privacy']
            )
        ]);
    }

    /**
     * @param FrontendUser $user
     * @param array $privacy
     * @throws \TYPO3\CMS\Extbase\Mvc\Exception\StopActionException
     * @throws \TYPO3\CMS\Extbase\Mvc\Exception\UnsupportedRequestTypeException
     * @throws \TYPO3\CMS\Extbase\Persistence\Exception\IllegalObjectTypeException
     * @throws \TYPO3\CMS\Extbase\Persistence\Exception\UnknownObjectException
     */
    public function privacyUpdateAction(FrontendUser $user, array $privacy = [])
    {
        // process privacy settings
        $this->frontendUserService->compilePrivacySettings(
            $user,
            $privacy,
            $this->settings['privacy']
        );

        $this->frontendUserRepository->update($user);

        $this->addFlashMessage(
            'Your privacy settings were updated',
            'Success',
            AbstractMessage::OK
        );

        $this->redirect('show');
    }

    public function editAction(FrontendUser $user)
    {
        $this->view->assign('user', $user);
    }

    public function updateAction(FrontendUser $user)
    {
        // store changes in database
        $this->frontendUserRepository->update($user);

        // clear cache entry for this page
        $currentPid = $this->configurationManager->getContentObject()->data['pid'];
        if ($currentPid > 0) {
            $this->cacheService->clearPageCache($currentPid);
        }

        $this->redirect(
            'show',
            null,
            null,
            [
                'user' => $user
            ]
        );
    }
}
