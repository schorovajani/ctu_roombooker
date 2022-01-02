<?php

namespace App\Controller;

use App\Entity\Team;
use App\Service\TeamService;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/api")
 */
class TeamController extends AbstractFOSRestController
{
	private TeamService $teamService;

	/**
	 * @param TeamService $teamService
	 */
	public function __construct(TeamService $teamService)
	{
		$this->teamService = $teamService;
	}

	/**
	 * @Rest\Get("/teams")
	 * @IsGranted("IS_AUTHENTICATED_REMEMBERED")
	 *
	 * @return Response
	 */
	public function routeGetTeams(): Response
	{
		$view = $this->view($this->teamService->getAll(), Response::HTTP_OK);
		$view->getContext()->setGroups(['listBuilding', 'listRoom', 'listTeam', 'listTeamDetails']);
		return $this->handleView($view);
	}

	/**
	 * @Rest\Get("/teams/{id}/{attr}", requirements={"id": "\d+"})
	 * @IsGranted("IS_AUTHENTICATED_REMEMBERED")
	 *
	 * @param Team $team
	 * @param string $attr
	 * @return Response
	 */
	public function routeGetTeamAttr(Team $team, string $attr): Response
	{
		switch ($attr) {
			case "rooms":
				$viewData = $this->teamService->getTeamRooms($team);
				break;

			case "users":
				$this->denyAccessUnlessGranted('GET_TEAM_USERS', $team);
				$viewData = $this->teamService->getTeamMembers($team);
				break;

			default:
				throw $this->createNotFoundException();
		}
		$view = $this->view($viewData, Response::HTTP_OK);
		$view->getContext()->setGroups(['listBuilding', 'listRoom', 'listTeam', 'listTeamRole', 'listUser']);
		return $this->handleView($view);
	}

	/**
	 * @Route("/teams/{id}", requirements={"id": "\d+"}, methods={"DELETE"})
	 * @IsGranted("ROLE_ADMIN")
	 *
	 * @param Team $team
	 * @return Response
	 */
	public function routeDeleteTeam(Team $team): Response
	{
		$error = [];
		if (!$team->getRooms()->isEmpty())
			$error['error'] =  'Delete or reassign rooms to the different team first';

		// TODO: Is this necessary?
		if (!$team->getChildren()->isEmpty())
			$error['error'] =  'Delete or reassign children to the different team first';

		if (!empty($error))
			return $this->handleView($this->view($error, Response::HTTP_BAD_REQUEST));

		$this->teamService->delete($team);
		return $this->handleView($this->view(null, Response::HTTP_NO_CONTENT));
	}
}
