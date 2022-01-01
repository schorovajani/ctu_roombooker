<?php

namespace App\Controller;

use App\Entity\Building;
use App\Service\BuildingService;
use App\Service\RoomService;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/api")
 */
class BuildingController extends AbstractFOSRestController
{
	private BuildingService $buildingService;
	private RoomService $roomService;

	/**
	 * @param BuildingService $buildingService
	 * @param RoomService $roomService
	 */
	public function __construct(BuildingService $buildingService, RoomService $roomService)
	{
		$this->buildingService = $buildingService;
		$this->roomService = $roomService;
	}

	/**
	 * @Rest\Get("/buildings")
	 *
	 * @return Response
	 */
	public function routeGetBuildings(): Response
	{
		return $this->handleView($this->view($this->buildingService->getAll(), Response::HTTP_OK));
	}

	/**
	 * @Rest\Get("/buildings/{id}/rooms", requirements={"id":"\d+"})
	 *
	 * @param Building $building
	 * @return Response
	 */
	public function routeGetBuildingRooms(Building $building): Response
	{
		if ($this->isGranted('IS_AUTHENTICATED_REMEMBERED'))
			$viewData = $building->getRooms();
		else
			$viewData = $this->roomService->getBy(['building' => $building, 'isPublic' => true]);

		$view = $this->view($viewData, Response::HTTP_OK);
		$view->getContext()->setGroups(['listBuilding', 'listRoom', 'listTeam']);
		return $this->handleView($view);
	}
}
