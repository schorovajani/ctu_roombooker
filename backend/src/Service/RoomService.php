<?php

namespace App\Service;

use App\Entity\Room;
use App\Repository\RoomRepository;

class  RoomService
{
	private RoomRepository $roomRepository;

	/**
	 * @param RoomRepository $roomRepository
	 */
	public function __construct(RoomRepository $roomRepository)
	{
		$this->roomRepository = $roomRepository;
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
	 * @param Room $room
	 * @return array
	 */
	public function getRoomUsers(Room $room): array
	{
		$users1 = [];
		$roles = $room->getRoomRoles();
		foreach ($roles as $role)
			$users1[] = $role->getUser();

		$users2 = [];
		$team = $room->getTeam();
		while ($team !== null) {
			$members = [];
			foreach ($team->getTeamRoles() as $role)
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
		$users = array_values($users);
		return $users;
	}


}


?>