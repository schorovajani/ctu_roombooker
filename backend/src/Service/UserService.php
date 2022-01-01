<?php

namespace App\Service;

use App\Entity\Account;
use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Security;

class UserService
{
	const ROLE_MANAGER = 'Manager';

	private UserRepository $userRepository;
	private EntityManagerInterface $entityManager;
	private TeamService $teamService;
	private Security $security;
	private RoomService $roomService;

	/**
	 * @param UserRepository $userRepository
	 * @param EntityManagerInterface $entityManager
	 * @param RoomService $roomService
	 * @param TeamService $teamService
	 * @param Security $security
	 */
	public function __construct(UserRepository         $userRepository,
								EntityManagerInterface $entityManager,
								RoomService            $roomService,
								TeamService            $teamService,
								Security               $security)
	{
		$this->userRepository = $userRepository;
		$this->entityManager = $entityManager;
		$this->teamService = $teamService;
		$this->security = $security;
		$this->roomService = $roomService;
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
	 * @param bool $onlyManagedRooms
	 * @return array
	 */
	public function getUserRooms(User $user, bool $onlyManagedRooms = false): array
	{
		/** @var Account $loggedInAccount */
		$loggedInAccount = $this->security->getUser();
		if ($this->security->isGranted('ROLE_ADMIN') && $loggedInAccount->getOwner() === $user)
			return $this->roomService->getAll();

		$rooms1 = [];
		$rooms2 = [];
		foreach ($user->getRoomRoles() as $roomRole)
			if (!$onlyManagedRooms || $roomRole->getRoleType()->getName() === self::ROLE_MANAGER)
				$rooms1[] = $roomRole->getRoom();
		foreach ($user->getTeamRoles() as $teamRole)
			if (!$onlyManagedRooms || $teamRole->getRoleType()->getName() === self::ROLE_MANAGER)
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
	 * @return array
	 */
	public function getUserRolesOverview(User $user): array
	{
		$roles = [];
		foreach ($user->getRoomRoles() as $role)
			if (!in_array('room' . $role->getRoleType()->getName(), $roles))
				$roles[] = 'room' . $role->getRoleType()->getName();

		foreach ($user->getTeamRoles() as $role)
			if (!in_array('team' . $role->getRoleType()->getName(), $roles))
				$roles[] = 'team' . $role->getRoleType()->getName();

		return $roles;
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
