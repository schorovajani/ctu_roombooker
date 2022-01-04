<?php

namespace App\Service;

use App\Entity\Account;
use App\Entity\Request;
use App\Entity\Status;
use App\Repository\RequestRepository;
use App\Repository\StatusRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Security;

class  RequestService
{
	private RequestRepository $requestRepository;
	private Security $security;
	private UserService $userService;
	private EntityManagerInterface $entityManager;
	private StatusRepository $statusRepository;

	/**
	 * @param EntityManagerInterface $entityManager
	 * @param RequestRepository $requestRepository
	 * @param Security $security
	 * @param UserService $userService
	 */
	public function __construct(EntityManagerInterface $entityManager,
															RequestRepository      $requestRepository,
															Security               $security,
															UserService            $userService,
															StatusRepository       $statusRepository)
	{
		$this->requestRepository = $requestRepository;
		$this->security = $security;
		$this->userService = $userService;
		$this->entityManager = $entityManager;
		$this->statusRepository = $statusRepository;
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

	/**
	 * @param Request $request
	 * @return void
	 */
	public function delete(Request $request): void
	{
		$this->entityManager->remove($request);
		$this->entityManager->flush();
	}

	/**
	 * @param Request $request
	 * @return void
	 */
	public function save(Request $request): void
	{
		if ($request->getStatus() === null)
			$request->setStatus($this->statusRepository->find(Status::PENDING_ID));
		$this->entityManager->persist($request);
		$this->entityManager->flush();
	}
}
