<?php
namespace App\Service;

use App\Repository\BuildingRepository;

class  BuildingService
{
	private BuildingRepository $buildingRepository;

	/**
	 * @param BuildingRepository $buildingRepository
	 */
	public function __construct(BuildingRepository $buildingRepository)
	{
		$this->buildingRepository = $buildingRepository;
	}

	/**
	 * @return array
	 */
	public function getAll(): array
	{
		return $this->buildingRepository->findAll();
	}
}
