<?php

namespace App\Controller;

use App\Entity\RoleType;
use App\Entity\Team;
use App\Entity\User;
use App\Service\TeamRoleService;
use App\Service\TeamService;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Entity;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/api")
 */
class TeamController extends AbstractFOSRestController
{
	private TeamRoleService $teamRoleService;
	private TeamService $teamService;

	/**
	 * @param TeamService $teamService
	 * @param TeamRoleService $teamRoleService
	 */
	public function __construct(TeamService $teamService, TeamRoleService $teamRoleService)
	{
		$this->teamRoleService = $teamRoleService;
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
	 * @Rest\Delete("/teams/{id}", requirements={"id": "\d+"})
	 * @IsGranted("ROLE_ADMIN")
	 *
	 * @param Team $team
	 * @return Response
	 */
	public function routeDeleteTeam(Team $team): Response
	{
		if (!$team->getRooms()->isEmpty())
			return $this->handleView($this->view(['error' => 'Delete or reassign rooms to a different team first'], Response::HTTP_BAD_REQUEST));
		if (!$team->getChildren()->isEmpty())
			return $this->handleView($this->view(['error' => 'Delete or reassign children to a different team first'], Response::HTTP_BAD_REQUEST));

		$this->teamService->delete($team);
		return $this->handleView($this->view(null, Response::HTTP_NO_CONTENT));
	}

	/**
	 * @Rest\Delete("/teams/{id}/users/{user_id}", requirements={"id": "\d+", "user_id": "\d+"})
	 * @Entity("user", expr="repository.find(user_id)")
	 * @IsGranted("IS_AUTHENTICATED_REMEMBERED")
	 *
	 * @param Team $team
	 * @param User $user
	 * @return Response
	 */
	public function routeDeleteUserTeamRole(Team $team, User $user): Response
	{
		$teamRole = $this->teamRoleService->get(['user' => $user, 'team' => $team]);
		if (!$this->isGranted('ROLE_ADMIN'))
			if ($teamRole->getRoleType()->getName() === RoleType::ROLE_MANAGER
				|| !in_array($this->getUser()->getOwner(), $this->teamService->getTeamManagers($team)))
				throw $this->createAccessDeniedException();

		$this->teamRoleService->delete($teamRole);
		return $this->handleView($this->view(null, Response::HTTP_NO_CONTENT));
	}
}
