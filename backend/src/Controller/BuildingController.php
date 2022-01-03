<?php

namespace App\Controller;

use App\Entity\Building;
use App\Service\BuildingService;
use App\Service\RoomService;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\ConstraintViolationListInterface;

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

	/**
	 * @Rest\Delete("/buildings/{id}", requirements={"id": "\d+"})
	 * @IsGranted("ROLE_ADMIN")
	 *
	 * @param Building $building
	 * @return Response
	 */
	public function routeDeleteBuilding(Building $building): Response
	{
		if (!$building->getRooms()->isEmpty())
			return $this->handleView($this->view([
				'error' => 'Delete or reassign rooms to a different building first',
			], Response::HTTP_BAD_REQUEST));

		$this->buildingService->delete($building);
		return $this->handleView($this->view(null, Response::HTTP_NO_CONTENT));
	}

	/**
	 * @Rest\Post("/buildings")
	 * @IsGranted("ROLE_ADMIN")
	 * @ParamConverter("building", converter="fos_rest.request_body")
	 *
	 * @param Building $building
	 * @param ConstraintViolationListInterface $validationErrors
	 * @return Response
	 */
	public function routePostBuilding(Building $building, ConstraintViolationListInterface $validationErrors): Response
	{
		if (count($validationErrors) > 0)
			return $this->handleView($this->view(['error' => $validationErrors], Response::HTTP_BAD_REQUEST));

		// https://insights.project-a.com/serializing-data-in-php-a-simple-primer-on-the-jms-serializer-and-fos-rest-f469d7d5b902
		$this->buildingService->save($building);
		return $this->handleView($this->view($building));
	}
}
