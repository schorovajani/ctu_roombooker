<?php

namespace App\Service;

use App\Entity\Team;
use App\Repository\TeamRepository;

class  TeamService
{
	private TeamRepository $repo;


	/**
	 * @param TeamRepository $repo
	 */
	public function __construct(TeamRepository $repo)
	{
		$this->repo = $repo;
	}

	/**
	 * @return array
	 */
	public function getAll(): array
	{
		return $this->repo->findAll();
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
		$teams = $this->getTeamChildrenRecursive($team);
		$members = [];
		foreach ($teams as $team)
			foreach ($team->getTeamRoles() as $role)
				$members[] = $role->getUser();
		$members = array_unique($members, SORT_REGULAR);
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

}


?>