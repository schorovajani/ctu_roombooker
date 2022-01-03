<?php

namespace App\Service;

use App\Entity\RoleType;
use App\Entity\Room;
use App\Entity\User;
use App\Repository\RoomRepository;
use Doctrine\ORM\EntityManagerInterface;

class RoomService
{
	private RoomRepository $roomRepository;
	private EntityManagerInterface $entityManager;

	/**
	 * @param EntityManagerInterface $entityManager
	 * @param RoomRepository $roomRepository
	 */
	public function __construct(EntityManagerInterface $entityManager, RoomRepository $roomRepository)
	{
		$this->roomRepository = $roomRepository;
		$this->entityManager = $entityManager;
	}

	/**
	 * @return array
	 */
	public function getAll(): array
	{
		return $this->roomRepository->findAll();
	}

	/**
	 * Mirror repository's `findBy` method.
	 * @param array $criteria
	 * @param array|null $orderBy
	 * @param null $limit
	 * @param null $offset
	 * @return array
	 */
	public function getBy(array $criteria, array $orderBy = null, $limit = null, $offset = null): array
	{
		return $this->roomRepository->findBy($criteria, $orderBy, $limit, $offset);
	}

	/**
	 * Get all users with access to Room $room.
	 *
	 * @param Room $room
	 * @param bool $onlyManagers
	 * @return User[]
	 */
	public function getRoomUsers(Room $room, bool $onlyManagers = false): array
	{
		$users1 = [];
		$roles = $room->getRoomRoles();
		foreach ($roles as $role)
			if (!$onlyManagers || $role->getRoleType()->getName() === RoleType::ROLE_MANAGER)
				$users1[] = $role->getUser();

		$users2 = [];
		$team = $room->getTeam();
		while ($team !== null) {
			$members = [];
			foreach ($team->getTeamRoles() as $role)
				if (!$onlyManagers || $role->getRoleType()->getName() === RoleType::ROLE_MANAGER)
					$members[] = $role->getUser();
			$users2 = array_merge($users2, $members);
			$team = $team->getParent();
		}

		$users = array_merge($users1, $users2);
		$users = array_unique($users, SORT_REGULAR);
		// array_unique retains keys even when the origin array didn't have them, e.g. [1, 1, 2] becomes ["0" => 1, "2" => 2].
		// Possible explanation: https://www.php.net/manual/en/function.array-unique.php
		// > [...] If multiple elements compare equal under the given flags, then the key and value of the first equal
		// > element will be retained.
		// It is possible that other places where array_unique is used are affected
		return array_values($users);
	}

	/**
	 * @param Room $room
	 * @return void
	 */
	public function delete(Room $room): void
	{
		foreach ($room->getRoomRoles() as $role)
			$this->entityManager->remove($role);
		foreach ($room->getRequests() as $request)
			$this->entityManager->remove($request);

		$this->entityManager->remove($room);
		$this->entityManager->flush();
	}

	/**
	 * @param Room $room
	 * @return void
	 */
	private function save(Room $room): void
	{
		$this->entityManager->persist($room);
		$this->entityManager->flush();
	}
}
