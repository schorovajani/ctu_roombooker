<?php

namespace App\Controller;

use App\Entity\Building;
use App\Repository\BuildingRepository;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/api")
 */
class  BuildingController extends \FOS\RestBundle\Controller\AbstractFOSRestController
{
	private BuildingRepository $repo;

	/**
	 * @param BuildingRepository $repo
	 */
	public function __construct(BuildingRepository $repo)
	{
		$this->repo = $repo;
	}

	/**
	 * @Rest\Get("/buildings")
	 * @return Response
	 */
	public function routeGetBuildings(): Response
	{
		return $this->handleView($this->view($this->repo->findAll(), Response::HTTP_OK));
	}

	/**
	 * @Rest\Get("/buildings/{id}/rooms",requirements={"id":"\d+"})
	 * @param Building $building
	 * @return Response
	 */
	public function routeGetBuildingRooms(Building $building): Response
	{
		return $this->handleView($this->view($building->getRooms(), Response::HTTP_OK));
	}
}


?>