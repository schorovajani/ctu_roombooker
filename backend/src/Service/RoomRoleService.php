<?php

namespace App\Service;

use App\Entity\RoomRole;
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
	 * @param RoomRole $roomRole
	 * @return void
	 */
	public function delete(RoomRole $roomRole): void
	{
		$this->entityManager->remove($roomRole);
		$this->entityManager->flush();
	}

	/**
	 * Mirror repository's `find` method.
	 *
	 * @param array $ids
	 * @param $lockMode
	 * @param $lockVersion
	 * @return RoomRole|null
	 */
	public function get(array $ids, $lockMode = null, $lockVersion = null): ?RoomRole
	{
		return $this->roomRoleRepository->find($ids, $lockMode, $lockVersion);
	}
}
