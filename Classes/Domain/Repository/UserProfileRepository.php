<?php
declare(strict_types=1);
namespace In2code\Userprofile\Domain\Repository;

use In2code\Userprofile\Domain\Model\UserProfile;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Persistence\QueryInterface;
use TYPO3\CMS\Extbase\Persistence\QueryResultInterface;
use TYPO3\CMS\Extbase\Persistence\Repository;

/**
 * Class UserProfileRepository
 */
class UserProfileRepository extends Repository
{

    /**
     * Overload Find by UID to also get hidden records
     *
     * @param int $uid fe_users UID
     * @return UserProfile
     */
    public function findByUid($uid)
    {
        $query = $this->createQuery();
        $and = [$query->equals('uid', $uid)];

        /** @var UserProfile $user */
        $user = $query->matching($query->logicalAnd($and))->execute()->getFirst();
        return $user;
    }

    /**
     * Find users by commaseparated usergroup list
     *
     * @param string $userGroupList commaseparated list of usergroup uids
     * @param array $settings Flexform and TypoScript Settings
     * @param array $filter Filter Array
     * @return QueryResultInterface|array
     */
    public function findByUsergroups($userGroupList, $settings, $filter)
    {
        $query = $this->createQuery();

        // where
        $and = [
            $query->greaterThan('uid', 0)
        ];
        if (!empty($userGroupList)) {
            $selectedUsergroups = GeneralUtility::trimExplode(',', $userGroupList, true);
            $logicalOr = [];
            foreach ($selectedUsergroups as $group) {
                $logicalOr[] = $query->contains('usergroup', $group);
            }
            $and[] = $query->logicalOr($logicalOr);
        }
        if (!empty($filter['searchword'])) {
            $searchwords = GeneralUtility::trimExplode(' ', $filter['searchword'], true);
            $fieldsToSearch = GeneralUtility::trimExplode(
                ',',
                $settings['list']['filter']['searchword']['fieldsToSearch'],
                true
            );
            foreach ($searchwords as $searchword) {
                $logicalOr = [];
                foreach ($fieldsToSearch as $searchfield) {
                    $logicalOr[] = $query->like($searchfield, '%' . $searchword . '%');
                }
                $and[] = $query->logicalOr($logicalOr);
            }
        }
        $query->matching($query->logicalAnd($and));

        // sorting
        $sorting = QueryInterface::ORDER_ASCENDING;
        if ($settings['list']['sorting'] === 'desc') {
            $sorting = QueryInterface::ORDER_DESCENDING;
        }
        $field = preg_replace('/[^a-zA-Z0-9_-]/', '', $settings['list']['orderby']);
        $query->setOrderings([$field => $sorting]);

        // set limit
        if ((int)$settings['list']['limit'] > 0) {
            $query->setLimit((int)$settings['list']['limit']);
        }

        $users = $query->execute();
        return $users;
    }
}
