<?php

namespace App\Service;

use App\Entity\Account;
use App\Entity\Request;
use App\Entity\Room;
use App\Entity\Status;
use App\Entity\User;
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
	private RoomService $roomService;

	/**
	 * @param EntityManagerInterface $entityManager
	 * @param RequestRepository $requestRepository
	 * @param Security $security
	 * @param UserService $userService
	 * @param StatusRepository $statusRepository
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
		$this->roomService = $roomService;
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
	 * Check if $user is permitted to create a request for $room.
	 * @note admin needs to be checked externally
	 * @param User $user
	 * @param Room $room
	 * @return bool
	 */
	public function canCreateRequest(User $user, Room $room): bool
	{
		return in_array($user, $this->roomService->getRoomUsers($room));
	}

	/**
	 * Check if $user can create assign other users as request creators.
	 * @note admin needs to be checked externally
	 * @param User $user
	 * @param Room $room
	 * @return bool
	 */
	public function canCreateRequestForOthers(User $user, Room $room): bool
	{
		return in_array($user, $this->roomService->getRoomUsers($room, true));
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
		$this->entityManager->persist($request);
		$this->entityManager->flush();
	}

	/**
	 * @param Request $request
	 * @return void
	 */
	public function setPending(Request $request): void
	{
		$request->setStatus($this->statusRepository->findOneBy(["name" => Status::STATUS_PENDING]));
	}

	/**
	 * @param Request $request
	 * @param Request $newRequest
	 * @return void
	 */
	public function update(Request $request, Request $newRequest): void
	{
		$request->setDescription($newRequest->getDescription());
		$request->setEventStart($newRequest->getEventStart());
		$request->setEventEnd($newRequest->getEventEnd());
		$request->setStatus($newRequest->getStatus());
		$request->setUser($newRequest->getUser());

		foreach ($request->getAttendees() as $attendee)
			$request->removeAttendee($attendee);

		foreach ($newRequest->getAttendees() as $attendee)
			$request->addAttendee($attendee);

		$this->save($request);
	}
}
