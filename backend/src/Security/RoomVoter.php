<?php

namespace App\Security;

use App\Entity\Account;
use App\Entity\Room;
use App\Service\RoomService;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\Security;

class RoomVoter extends Voter
{
	const GET_ROOM_USERS = 'GET_ROOM_USERS';

	private RoomService $roomService;
	private Security $security;

	public function __construct(RoomService $roomService, Security $security)
	{
		$this->roomService = $roomService;
		$this->security = $security;
	}

	protected function supports(string $attribute, $subject): bool
	{
		return in_array($attribute, [self::GET_ROOM_USERS])
			&& $subject instanceof Room;
	}

	protected function voteOnAttribute(string $attribute, $subject, TokenInterface $token): bool
	{
		/** @var Account $loggedInAccount */
		$loggedInAccount = $token->getUser();

		// if the user is anonymous, do not grant access
		if (!$loggedInAccount instanceof Account)
			return false;

		/** @var Room $room */
		$room = $subject;

		switch ($attribute) {
			case self::GET_ROOM_USERS:
				return $this->canGetRoomUsers($room, $loggedInAccount);
		}

		throw new \LogicException('This code should not be reached!');
	}

	private function canGetRoomUsers(Room $room, Account $loggedInAccount): bool
	{
		return ($this->security->isGranted('ROLE_ADMIN')
			|| in_array($loggedInAccount->getOwner(), $this->roomService->getRoomUsers($room, true)));
	}
}
