<?php

namespace App\Service;

use App\Entity\Status;
use App\Repository\StatusRepository;

class StatusService
{
	private StatusRepository $statusRepository;

	/**
	 * @param StatusRepository $statusRepository
	 */
	public function __construct(StatusRepository $statusRepository)
	{
		$this->statusRepository = $statusRepository;
	}

	/**
	 * Mirror repository's `find` method.
	 *
	 * @param mixed $id
	 * @param int|null $lockMode
	 * @param int|null $lockVersion
	 * @return Status|null
	 */
	public function get($id, $lockMode = null, $lockVersion = null): ?Status
	{
		return $this->statusRepository->find($id, $lockMode, $lockVersion);
	}
}
