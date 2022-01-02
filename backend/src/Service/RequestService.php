<?php

namespace App\Service;

use App\Entity\Account;
use App\Entity\Request;
use App\Repository\RequestRepository;
use Symfony\Component\Security\Core\Security;

class  RequestService
{
	private RequestRepository $requestRepository;
	private Security $security;
	private UserService $userService;

	/**
	 * @param RequestRepository $requestRepository
	 * @param Security $security
	 * @param UserService $userService
	 */
	public function __construct(RequestRepository $requestRepository, Security $security, UserService $userService)
	{
		$this->requestRepository = $requestRepository;
		$this->security = $security;
		$this->userService = $userService;
	}

	/**
	 * @return Request[]
	 */
	public function getAll(): array
	{
		return $this->requestRepository->findAll();
	}

	/**
	 * @return Request[]
	 */
	public function getAdministeredRequests(): array
	{
		if ($this->security->isGranted('ROLE_ADMIN'))
			return $this->getAll();

		/** @var Account $loggedInAccount */
		$loggedInAccount = $this->security->getUser();

		$managedRooms = $this->userService->getUserRooms($loggedInAccount->getOwner(), true);
		$requests = [];
		foreach ($this->getAll() as $request)
			if (in_array($request->getRoom(), $managedRooms))
				$requests[] = $request;

		return $requests;
	}
}
