<?php

namespace App\Service;

use App\Entity\Account;
use App\Repository\AccountRepository;
use Doctrine\ORM\EntityManagerInterface;

class AccountService
{
	private AccountRepository $accountRepository;
	private EntityManagerInterface $entityManager;

	/**
	 * @param AccountRepository $accountRepository
	 * @param EntityManagerInterface $entityManager
	 */
	public function __construct(AccountRepository $accountRepository, EntityManagerInterface $entityManager)
	{
		$this->accountRepository = $accountRepository;
		$this->entityManager = $entityManager;
	}

	/**
	 * @return array
	 */
	public function getAll(): array
	{
		return $this->accountRepository->findAll();
	}

	/**
	 * Mirror repository's `findBy` method.
	 *
	 * @param array $criteria
	 * @param array|null $orderBy
	 * @param $limit
	 * @param $offset
	 * @return array
	 */
	public function getBy(array $criteria, array $orderBy = null, $limit = null, $offset = null): array
	{
		return $this->accountRepository->findBy($criteria, $orderBy, $limit, $offset);
	}

	/**
	 * @param Account $account
	 * @return void
	 */
	public function delete(Account $account): void
	{
		$this->entityManager->remove($account);
		$this->entityManager->flush();
	}
}
