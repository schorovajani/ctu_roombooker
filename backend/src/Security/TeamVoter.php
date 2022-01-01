<?php

namespace App\Security;

use App\Entity\Account;
use App\Entity\Team;
use App\Service\TeamService;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\Security;

class TeamVoter extends Voter
{
	const GET_TEAM_USERS = 'GET_TEAM_USERS';

	private TeamService $teamService;
	private Security $security;

	public function __construct(TeamService $teamService, Security $security)
	{
		$this->teamService = $teamService;
		$this->security = $security;
	}

	protected function supports(string $attribute, $subject): bool
	{
		return in_array($attribute, [self::GET_TEAM_USERS])
			&& $subject instanceof Team;
	}

	protected function voteOnAttribute(string $attribute, $subject, TokenInterface $token): bool
	{
		/** @var Account $loggedInAccount */
		$loggedInAccount = $token->getUser();

		// if the user is anonymous, do not grant access
		if (!$loggedInAccount instanceof Account)
			return false;

		/** @var Team $team */
		$team = $subject;

		switch ($attribute) {
			case self::GET_TEAM_USERS:
				return $this->canGetTeamUsers($team, $loggedInAccount);
		}

		throw new \LogicException('This code should not be reached!');
	}

	private function canGetTeamUsers(Team $team, Account $loggedInAccount): bool
	{
		return ($this->security->isGranted('ROLE_ADMIN') ||
			in_array($loggedInAccount->getOwner(), $this->teamService->getTeamManagers($team)));
	}
}
