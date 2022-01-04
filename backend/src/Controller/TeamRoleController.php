<?php

namespace App\Controller;

use App\Entity\TeamRole;
use App\Service\TeamRoleService;
use FOS\RestBundle\Controller\Annotations as Rest;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\ConstraintViolationListInterface;

/**
 * @Route("/api")
 */
class TeamRoleController extends \FOS\RestBundle\Controller\AbstractFOSRestController
{
	private TeamRoleService $teamRoleService;

	/**
	 * @param TeamRoleService $teamRoleService
	 */
	public function __construct(TeamRoleService $teamRoleService)
	{
		$this->teamRoleService = $teamRoleService;
	}

	/**
	 * @Rest\Post("/teamRoles")
	 * @ParamConverter("teamRole", converter="fos_rest.request_body")
	 *
	 * @param TeamRole $teamRole
	 * @param ConstraintViolationListInterface $validationErrors
	 * @return Response
	 */
	public function routePostTeamRole(TeamRole $teamRole, ConstraintViolationListInterface $validationErrors): Response
	{
		if (count($validationErrors) > 0)
			return $this->handleView($this->view(["error" => $validationErrors], Response::HTTP_BAD_REQUEST));

		$this->teamRoleService->save($teamRole);
		return $this->handleView($this->view($teamRole, Response::HTTP_CREATED));
	}
}
