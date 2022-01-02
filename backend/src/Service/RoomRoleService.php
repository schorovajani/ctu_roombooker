<?php

namespace App\Service;

use App\Entity\Room;
use App\Entity\User;
use App\Repository\RoomRoleRepository;
use Doctrine\ORM\EntityManagerInterface;

class RoomRoleService
{
	private EntityManagerInterface $entityManager;
	private RoomRoleRepository $roomRoleRepository;

	/**
	 * @param EntityManagerInterface $entityManager
	 * @param RoomRoleRepository $roomRoleRepository
	 */
	public function __construct(EntityManagerInterface $entityManager, RoomRoleRepository $roomRoleRepository)
	{
		$this->entityManager = $entityManager;
		$this->roomRoleRepository = $roomRoleRepository;
	}

	/**
	 * @param Room $room
	 * @param User $user
	 * @return void
	 */
	public function removeRoomRole(Room $room, User $user): void
	{
		$roomRole = $this->roomRoleRepository->find(['user' => $user, 'room' => $room]);
		$this->entityManager->remove($roomRole);
		$this->entityManager->flush();
	}
}
