<?php

namespace App\Controller;

use App\Entity\Building;
use App\Service\BuildingService;
use App\Service\RoomService;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use JMS\Serializer\SerializerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/api")
 */
class BuildingController extends AbstractFOSRestController
{
	private BuildingService $buildingService;
	private RoomService $roomService;
	private SerializerInterface $serializer;

	/**
	 * @param BuildingService $buildingService
	 * @param RoomService $roomService
	 * @param SerializerInterface $serializer
	 */
	public function __construct(BuildingService $buildingService, RoomService $roomService, SerializerInterface $serializer)
	{
		$this->buildingService = $buildingService;
		$this->roomService = $roomService;
		$this->serializer = $serializer;
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
	 *
	 * @param Request $request
	 * @return Response
	 */
	public function routePostBuilding(Request $request): Response
	{
		// https://insights.project-a.com/serializing-data-in-php-a-simple-primer-on-the-jms-serializer-and-fos-rest-f469d7d5b902
		$building = $this->serializer->deserialize($request->getContent(), Building::class, 'json');
		$this->buildingService->save($building);
		return new Response($this->serializer->serialize($building, "xml"));
	}
}
