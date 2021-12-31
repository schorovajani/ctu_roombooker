<?php

namespace App\Service;

use App\Entity\Building;
use App\Repository\BuildingRepository;
use App\Repository\RoomRepository;
use Symfony\Component\Security\Core\Security;

class BuildingService
{
	private BuildingRepository $buildingRepository;
	private Security $security;
	private RoomRepository $roomRepository;

	/**
	 * @param BuildingRepository $buildingRepository
	 * @param RoomRepository $roomRepository
	 * @param Security $security
	 */
	public function __construct(BuildingRepository $buildingRepository, RoomRepository $roomRepository, Security $security)
	{
		$this->buildingRepository = $buildingRepository;
		$this->security = $security;
		$this->roomRepository = $roomRepository;
	}

	/**
	 * @return array
	 */
	public function getAll(): array
	{
		return $this->buildingRepository->findAll();
	}

	/**
	 * @param Building $building
	 * @return array
	 */
	public function getRooms(Building $building): array
	{
		if ($this->security->isGranted('ROLE_USER'))
			return $building->getRooms()->toArray();

		return $this->roomRepository->findBy([
			'building' => $building,
			'isPublic' => true,
		]);
	}
}
