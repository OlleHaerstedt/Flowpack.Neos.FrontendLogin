<?php
namespace Bitmotion\Neos\FrontendLogin\Domain\Repository;

/*                                                                             *
 * This script belongs to the TYPO3 Flow package "Bitmotion.Neos.FrontendLogin".*
 *                                                                             */

use TYPO3\Flow\Annotations as Flow;
use TYPO3\Flow\Persistence\Repository;
use TYPO3\Flow\Security\Account;
use Bitmotion\Neos\FrontendLogin\Domain\Model\User;

/**
 * @Flow\Scope("singleton")
 */
class UserRepository extends Repository {

	/**
	 * @param Account $account
	 * @return User
	 */
	public function findOneHavingAccount(Account $account) {
		$query = $this->createQuery();
		return
			$query->matching(
				$query->contains('accounts', $account)
			)
			->execute()
			->getFirst();
	}

  /**
   * Return all users with at least one account with role $role
   *
   * @param string $roleIdentifier
   * @return array result
   */
  public function findWithRole($roleIdentifier) {
    $query = $this->createQuery();
    return
      $query->matching(
        $query->like('accounts.roleIdentifiers', '%' . $roleIdentifier . '%')
      )
      ->execute();
  }

}
