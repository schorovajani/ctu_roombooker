<?php

namespace App\Service;

use App\Repository\RequestRepository;

class  RequestService
{
	private RequestRepository $requestRepository;

	/**
	 * @param RequestRepository $requestRepository
	 */
	public function __construct(RequestRepository $requestRepository)
	{
		$this->requestRepository = $requestRepository;
	}

	/**
	 * @return array
	 */
	public function getAll(): array
	{
		return $this->requestRepository->findAll();
	}
}
