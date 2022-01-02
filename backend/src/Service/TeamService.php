<?php

namespace App\Service;

use App\Entity\RoleType;
use App\Entity\Team;
use App\Entity\User;
use App\Repository\TeamRepository;

class TeamService
{
	private TeamRepository $teamRepository;

	/**
	 * @param TeamRepository $teamRepository
	 */
	public function __construct(TeamRepository $teamRepository)
	{
		$this->teamRepository = $teamRepository;
	}

	/**
	 * @return array
	 */
	public function getAll(): array
	{
		return $this->teamRepository->findAll();
	}

	/**
	 * @param Team $team
	 * @return array
	 */
	public function getTeamRooms(Team $team): array
	{
		$children = $this->getTeamChildrenRecursive($team);
		$rooms = [];
		foreach ($children as $child)
			$rooms = array_merge($rooms, $child->getRooms()->toArray());
		return $rooms;
	}

	/**
	 * Return all team members.
	 * @param Team $team
	 * @return array
	 */
	public function getTeamMembers(Team $team): array
	{
		$members = [];
		foreach ($team->getTeamRoles() as $role)
			$members[] = $role->getUser();
		return $members;
	}

	/**
	 * @param Team $team
	 * @return array
	 */
	private function getTeamChildrenRecursive(Team $team): array
	{
		return array_reduce($team->getChildren()->toArray(),
			function (array $carry, Team $item) {
				return array_merge($carry, $this->getTeamChildrenRecursive($item));
			},
			[$team]);
	}

	/**
	 * @param Team $team
	 * @return User[]
	 */
	public function getTeamManagers(Team $team): array
	{
		$managers = [];
		while ($team !== null) {
			foreach ($team->getTeamRoles() as $role)
				if ($role->getRoleType()->getName() === RoleType::ROLE_MANAGER)
					$managers[] = $role->getUser();

			$team = $team->getParent();
		}
		return $managers;
	}
}
