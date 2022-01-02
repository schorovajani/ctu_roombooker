<?php

namespace App\Service;

use App\Entity\RoleType;
use App\Entity\Team;
use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;

class UserService
{
	private UserRepository $userRepository;
	private EntityManagerInterface $entityManager;
	private TeamService $teamService;

	/**
	 * @param UserRepository $userRepository
	 * @param EntityManagerInterface $entityManager
	 * @param TeamService $teamService
	 */
	public function __construct(UserRepository $userRepository, EntityManagerInterface $entityManager, TeamService $teamService)
	{
		$this->userRepository = $userRepository;
		$this->entityManager = $entityManager;
		$this->teamService = $teamService;
	}

	/**
	 * @return User[]
	 */
	public function getAll(): array
	{
		return $this->userRepository->findAll();
	}

	/**
	 * @param User $user
	 * @param bool $onlyAdministeredRooms
	 * @return array
	 */
	public function getUserRooms(User $user, bool $onlyAdministeredRooms = false): array
	{
		$rooms1 = [];
		$rooms2 = [];
		foreach ($user->getRoomRoles() as $roomRole)
			if (!$onlyAdministeredRooms || $roomRole->getRoleType()->getName() === RoleType::ROLE_MANAGER)
				$rooms1[] = $roomRole->getRoom();
		foreach ($user->getTeamRoles() as $teamRole)
			if (!$onlyAdministeredRooms || $teamRole->getRoleType()->getName() === RoleType::ROLE_MANAGER)
				$rooms2 = array_merge($rooms2, $this->teamService->getTeamRooms($teamRole->getTeam()));

		return array_unique(array_merge($rooms1, $rooms2), SORT_REGULAR);
	}

	/**
	 * @param User $user
	 * @return array
	 */
	public function getUserRequests(User $user): array
	{
		$created = $user->getRequests()->toArray();
		$invitedTo = $user->getAttendees()->toArray();
		return array_merge($created, $invitedTo);
	}

	/**
	 * @param User $user
	 */
	public function save(User $user): void
	{
		$this->entityManager->persist($user);
		$this->entityManager->flush();
	}

	/**
	 * @param User $user
	 * @return Team[]
	 */
	public function getUserAdministeredTeams(User $user): array
	{
		$teams = [];
		foreach ($user->getTeamRoles() as $role)
			if ($role->getRoleType()->getName() === RoleType::ROLE_MANAGER)
				$teams = array_merge($teams, $this->teamService->getTeamChildrenRecursive($role->getTeam()));

		return array_unique($teams, SORT_REGULAR);
	}
}
