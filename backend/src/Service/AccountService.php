<?php

namespace App\Service;

use App\Repository\AccountRepository;

class AccountService
{
	private AccountRepository $accountRepository;

	/**
	 * @param AccountRepository $accountRepository
	 */
	public function __construct(AccountRepository $accountRepository)
	{
		$this->accountRepository = $accountRepository;
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
}
