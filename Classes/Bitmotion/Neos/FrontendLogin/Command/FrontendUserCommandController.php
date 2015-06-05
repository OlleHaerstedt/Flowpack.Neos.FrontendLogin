<?php
namespace Bitmotion\Neos\FrontendLogin\Command;

/*                                                                             *
 * This script belongs to the TYPO3 Flow package "Bitmotion.Neos.FrontendLogin".*
 *                                                                             */

use TYPO3\Flow\Annotations as Flow;
use TYPO3\Flow\Cli\CommandController;
use TYPO3\Neos\Domain\Service\UserService;
use Bitmotion\Neos\FrontendLogin\Domain\Model\User;
use Bitmotion\Neos\FrontendLogin\Domain\Service\FrontendUserService;

/**
 * @Flow\Scope("singleton")
 */
class FrontendUserCommandController extends CommandController {

	/**
   * Bitmotion user service
   *
	 * @Flow\Inject
	 * @var FrontendUserService
	 */
	protected $frontEndUserService;

	/**
   * Neos user service
   *
	 * @Flow\Inject
	 * @var UserService
	 */
	protected $userService;

	/**
	 * Create a new "Frontend user"
	 *
	 * @param string $username The username of the user to be created, used as an account identifier for the newly created account
	 * @param string $password Password of the user to be created
	 * @param string $givenName First name of the user to be created
	 * @param string $familyName Last name of the user to be created
	 * @return void
	 */
	public function createCommand($username, $password, $givenName, $familyName) {
		$user = $this->frontEndUserService->getUser($username);
		if ($user instanceof User) {
			$this->outputLine('The username "%s" is already in use', array($username));
			$this->quit(1);
		}
		$user = new User($givenName, $familyName);
		$this->frontEndUserService->addUser($user, $username, $password);
	}

  /**
   * Add a role to a front-end user
   *
   * @param string $username
   * @param string $roleIdentifier
   */
  public function addRoleCommand($username, $roleIdentifier) {
    $user = $this->frontEndUserService->getUser($username);

    if (!($user instanceof User)) {
      $this->outputLine('No such user: %s', array($username));
      $this->quit(1);
    }
    $accounts = $user->getAccounts();

    assert(count($accounts) === 1, 'Only one account per user');

    $this->userService->addRoleToAccount($accounts[0], $roleIdentifier);
  }

}
