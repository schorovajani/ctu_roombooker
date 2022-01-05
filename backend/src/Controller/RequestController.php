<?php

namespace App\Controller;

use App\Entity\Account;
use App\Entity\Request;
use App\Entity\Status;
use App\Service\RequestService;
use App\Service\StatusService;
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
class RequestController extends AbstractFOSRestController
{
	private RequestService $requestService;
	private StatusService $statusService;

	/**
	 * @param RequestService $requestService
	 * @param StatusService $statusService
	 */
	public function __construct(RequestService $requestService, StatusService $statusService)
	{
		$this->requestService = $requestService;
		$this->statusService = $statusService;
	}

	/**
	 * Returns requests which can logged-in user manage (approve/deny), it includes also requests which are already approved
	 *
	 * @Rest\Get("/requests")
	 * @IsGranted("IS_AUTHENTICATED_REMEMBERED")
	 *
	 * @return Response
	 */
	public function routeGetRequests(): Response
	{
		$view = $this->view($this->requestService->getAdministeredRequests(), Response::HTTP_OK);
		$view->getContext()->setGroups(['listBuilding', 'listRequest', 'listRoom', 'listStatus', 'listUser']);
		return $this->handleView($view);
	}

	/**
	 * @Rest\Get("/requests/{id}", requirements={"id":"\d+"})
	 * @IsGranted("IS_AUTHENTICATED_REMEMBERED")
	 *
	 * @param Request $request
	 * @return Response
	 */
	public function routeGetRequest(Request $request): Response
	{
		$view = $this->view($request, Response::HTTP_OK);
		$view->getContext()->setGroups(['listBuilding', 'listRequest', 'listRoom', 'listStatus', 'listUser']);
		return $this->handleView($view);
	}

	/**
	 * @Rest\Delete("/requests/{id}", requirements={"id": "\d+"})
	 * @IsGranted("IS_AUTHENTICATED_REMEMBERED")
	 *
	 * @param Request $request
	 * @return Response
	 */
	public function routeDeleteRequest(Request $request): Response
	{
		if (!$this->isGranted('ROLE_ADMIN') && $this->getUser()->getOwner() !== $request->getUser())
			throw $this->createAccessDeniedException();
		$this->requestService->delete($request);
		return $this->handleView($this->view(null, Response::HTTP_NO_CONTENT));
	}

	/**
	 * @Rest\Post("/requests")
	 * @ParamConverter("request", converter="fos_rest.request_body")
	 * @IsGranted("IS_AUTHENTICATED_REMEMBERED")
	 *
	 * @param Request $request
	 * @param ConstraintViolationListInterface $validationErrors
	 * @return Response
	 */
	public function routePostRequest(Request $request, ConstraintViolationListInterface $validationErrors): Response
	{
		/** @var Account $loggedInUser */
		$loggedInUser = $this->getUser();

		// is authorized?
		if (!($this->isGranted('ROLE_ADMIN')
			|| $this->requestService->canCreateRequest($loggedInUser->getOwner(), $request->getRoom())))
			throw $this->createAccessDeniedException();

		// automatic validation
		if (count($validationErrors) > 0)
			return $this->handleView($this->view(["error" => $validationErrors], Response::HTTP_BAD_REQUEST));

		// invalid data (non-existent ids etc.)
		if ($request->getUser() === null || $request->getRoom() === null || in_array(null, $request->getAttendees()->toArray()))
			return $this->handleView($this->view(["error" => "Invalid data"], Response::HTTP_BAD_REQUEST));

		// creating requests for others
		if (!($this->isGranted('ROLE_ADMIN')
			|| $this->requestService->canCreateRequestForOthers($request->getUser(), $request->getRoom())))
			return $this->handleView($this->view(["error" => "You cannot create requests on behalf of other users"], Response::HTTP_BAD_REQUEST));

		// self-approval
		if ($request->getStatus() == null
			|| $request->getStatus()->getName() != Status::STATUS_PENDING)
			$this->requestService->setPending($request);

		// time range
		if (!$this->validateDate($request))
			return $this->handleView($this->view(["error" => "Invalid date"], Response::HTTP_BAD_REQUEST));

		$this->requestService->save($request);

		$view = $this->view($request, Response::HTTP_CREATED);
		$view->getContext()->setGroups(['listBuilding', 'listRequest', 'listRoom', 'listStatus', 'listUser']);
		return $this->handleView($view);
	}

	/**
	 * @Rest\Put("/requests/{id}", requirements={"id": "\d+"})
	 * @ParamConverter("request")
	 * @ParamConverter("newRequest", converter="fos_rest.request_body")
	 * @IsGranted("IS_AUTHENTICATED_REMEMBERED")
	 *
	 * @param Request $request
	 * @param Request $newRequest
	 * @param ConstraintViolationListInterface $validationErrors
	 * @return Response
	 */
	public function routePutRequest(Request $request, Request $newRequest, ConstraintViolationListInterface $validationErrors): Response
	{
		if (count($validationErrors) > 0)
			return $this->handleView($this->view(['error' => $validationErrors], Response::HTTP_BAD_REQUEST));

		if (!in_array($request, $this->requestService->getAdministeredRequests()))
			throw $this->createAccessDeniedException();

		if (!$newRequest->getUser() || !$newRequest->getStatus() || !$newRequest->getRoom() || in_array(null, $newRequest->getAttendees()->toArray()))
			return $this->handleView($this->view(['error' => 'Invalid data'], Response::HTTP_BAD_REQUEST));

		$this->requestService->update($request, $newRequest);

		$view = $this->view($request, Response::HTTP_OK);
		$view->getContext()->setGroups(['listBuilding', 'listRequest', 'listRoom', 'listStatus', 'listUser']);
		return $this->handleView($view);
	}

	/**
	 * @Rest\Patch("/requests/{id}", requirements={"id": "\d+"})
	 * @IsGranted("IS_AUTHENTICATED_REMEMBERED")
	 *
	 * @param Request $request
	 * @param \Symfony\Component\HttpFoundation\Request $httpRequest
	 * @return Response
	 */
	public function routePatchRequest(Request $request, \Symfony\Component\HttpFoundation\Request $httpRequest): Response
	{
		if (!in_array($request, $this->requestService->getAdministeredRequests()))
			throw $this->createAccessDeniedException();

		$newStatusId = $httpRequest->request->get("status");
		if (!is_int($newStatusId))
			return $this->handleView($this->view(['error' => 'Invalid data'], Response::HTTP_BAD_REQUEST));
		$newStatus = $this->statusService->get($newStatusId);
		if (!$newStatus)
			return $this->handleView($this->view(['error' => 'Invalid data'], Response::HTTP_BAD_REQUEST));

		$request->setStatus($newStatus);
		$this->requestService->save($request);

		$view = $this->view($request, Response::HTTP_OK);
		$view->getContext()->setGroups(['listBuilding', 'listRequest', 'listRoom', 'listStatus', 'listUser']);
		return $this->handleView($view);
	}

	/**
	 * Validate $request's timestamps
	 * @param Request $request
	 * @return bool
	 */
	private function validateDate(Request $request): bool
	{
		// sensible dates
		$now = date_create('now');
		if ($request->getEventStart() < $request->getEventEnd()
			|| $request->getEventStart() < $now)
			return false;

		// overlapping
		$requests = $this->requestService->getAll();
		foreach ($requests as $xRequest)
			if ($this->overlap($request, $xRequest))
				return false;

		// 15+ minutes
		if (date_diff($request->getEventStart(), $request->getEventEnd())->i < 15)
			return false;
		return true;
	}

	/**
	 * Check if two requests overlap time-wise.
	 * @param Request $a
	 * @param Request $b
	 * @return bool
	 */
	private function overlap(Request $a, Request $b): bool
	{
		if ($a->getEventStart() <= $b->getEventStart() && $a->getEventEnd() >= $b->getEventEnd())
			return true;
		return ($a->getEventStart() >= $b->getEventStart()
				&& $a->getEventStart() <= $b->getEventEnd())
			|| ($a->getEventEnd() >= $b->getEventStart()
				&& $a->getEventEnd() <= $b->getEventEnd());
	}
}
