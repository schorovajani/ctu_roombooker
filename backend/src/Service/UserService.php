<?php

namespace App\Service;

use App\Entity\Account;
use App\Entity\RoleType;
use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Security;

class UserService
{
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
	 * @param bool $onlyAdministeredRooms
	 * @return array
	 */
	public function getUserRooms(User $user, bool $onlyAdministeredRooms = false): array
	{
		/** @var Account $loggedInAccount */
		$loggedInAccount = $this->security->getUser();
		// return all rooms if logged-in user is admin && this method is called with his user object
		if ($this->security->isGranted('ROLE_ADMIN') && $loggedInAccount->getOwner() === $user)
			return $this->roomService->getAll();

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
}
