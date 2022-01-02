<?php

namespace App\Controller;

use App\Entity\Account;
use App\Service\AccountService;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/api")
 */
class AccountController extends AbstractFOSRestController
{
	private AccountService $accountService;

	/**
	 * @param AccountService $accountService
	 */
	public function __construct(AccountService $accountService)
	{
		$this->accountService = $accountService;
	}

	/**
	 * @Rest\Get("/accounts")
	 * @IsGranted("ROLE_ADMIN")
	 *
	 * @return Response
	 */
	public function routeGetAccounts(): Response
	{
		$view = $this->view($this->accountService->getAll(), Response::HTTP_OK);
		$view->getContext()->setGroups(['listAccount', 'listUser']);
		return $this->handleView($view);
	}

	/**
	 * @Rest\Get("/accounts/{id}", requirements={"id":"\d+"})
	 * @IsGranted("IS_AUTHENTICATED_REMEMBERED")
	 *
	 * @param Account $account
	 * @return Response
	 */
	public function routeGetAccount(Account $account): Response
	{
		if (!$this->isGranted('ROLE_ADMIN') && $this->getUser()->getOwner() !== $account->getOwner())
			throw $this->createAccessDeniedException();

		$view = $this->view($account, Response::HTTP_OK);
		$view->getContext()->setGroups(['listAccount', 'listUser']);
		return $this->handleView($view);
	}
}
