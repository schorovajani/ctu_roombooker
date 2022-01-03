<?php

namespace App\Service;

use App\Entity\Building;
use App\Repository\BuildingRepository;
use Doctrine\ORM\EntityManagerInterface;

class BuildingService
{
	private BuildingRepository $buildingRepository;
	private EntityManagerInterface $entityManager;

	/**
	 * @param BuildingRepository $buildingRepository
	 * @param EntityManagerInterface $entityManager
	 */
	public function __construct(BuildingRepository $buildingRepository, EntityManagerInterface $entityManager)
	{
		$this->buildingRepository = $buildingRepository;
		$this->entityManager = $entityManager;
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
	 * @return void
	 */
	public function delete(Building $building): void
	{
		$this->entityManager->remove($building);
		$this->entityManager->flush();
	}
}
