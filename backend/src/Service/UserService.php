<?php

namespace App\Service;

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
	 * @param int $id
	 * @return User|null
	 */
	public function getById(int $id): ?User
	{
		return $this->userRepository->find($id);
	}

	/**
	 * @param User $user
	 * @return array
	 */
	public function getUserRooms(User $user): array
	{
		$rooms1 = [];
		$rooms2 = [];
		foreach ($user->getRoomRoles() as $roomRole)
			$rooms1[] = $roomRole->getRoom();
		foreach ($user->getTeamRoles() as $teamRole)
			$rooms2 = array_merge($rooms2, $this->teamService->getTeamRooms($teamRole->getTeam()));
		$data = array_unique(array_merge($rooms1, $rooms2), SORT_REGULAR);
		return $data;
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
}
