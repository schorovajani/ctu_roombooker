<?php

namespace App\Service;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;

class UserService
{
	private UserRepository $userRepository;
	private EntityManagerInterface $entityManager;

	/**
	 * @param UserRepository $userRepository
	 * @param EntityManagerInterface $entityManager
	 */
	public function __construct(UserRepository $userRepository, EntityManagerInterface $entityManager)
	{
		$this->userRepository = $userRepository;
		$this->entityManager = $entityManager;
	}

	/**
	 * @return User[]
	 */
	public function getAll(): array
	{
		return $this->userRepository->findAll();
	}

	/**
	 * @param int $id
	 * @return User|null
	 */
	public function getById(int $id): ?User
	{
		return $this->userRepository->find($id);
	}

	/**
	 * @param User $user
	 */
	public function save(User $user): void
	{
		$this->entityManager->persist($user);
		$this->entityManager->flush();
	}
}
