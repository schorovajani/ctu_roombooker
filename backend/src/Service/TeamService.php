<?php

namespace App\Service;

use App\Entity\RoleType;
use App\Entity\Team;
use App\Entity\User;
use App\Repository\TeamRepository;
use Doctrine\ORM\EntityManagerInterface;

class TeamService
{
	private TeamRepository $teamRepository;
	private EntityManagerInterface $entityManager;
	private RoomService $roomService;

	/**
	 * @param EntityManagerInterface $entityManager
	 * @param TeamRepository $teamRepository
	 */
	public function __construct(EntityManagerInterface $entityManager, TeamRepository $teamRepository, RoomService $roomService)
	{
		$this->teamRepository = $teamRepository;
		$this->entityManager = $entityManager;
		$this->roomService = $roomService;
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
	public function getTeamChildrenRecursive(Team $team): array
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

	/**
	 * @param Team $team
	 * @return void
	 */
	public function delete(Team $team): void
	{
		foreach ($team->getTeamRoles() as $role)
			$this->entityManager->remove($role);

		$this->entityManager->remove($team);
		$this->entityManager->flush();
	}

	/**
	 * @param Team $team
	 * @return void
	 */
	public function save(Team $team): void
	{
		foreach ($team->getRooms() as $room)
			$room->setTeam($team);

		$this->entityManager->persist($team);
		$this->entityManager->flush();
		$this->entityManager->refresh($team);
	}

	/**
	 * @param Team $team
	 * @param Team $newTeam
	 * @return void
	 */
	public function update(Team $team, Team $newTeam): void
	{
		$team->setName($newTeam->getName());
		$team->setParent($newTeam->getParent());

		foreach ($team->getRooms() as $room)
			$team->removeRoom($room);

		foreach ($newTeam->getRooms() as $room)
			$team->addRoom($room);

		$this->save($team);
	}
}
