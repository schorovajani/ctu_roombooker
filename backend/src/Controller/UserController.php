<?php

namespace App\Controller;

use App\Service\UserService;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/api")
 */
class UserController extends AbstractFOSRestController
{
  private UserService $userService;

  /**
   * @param UserService $userService
   */
  public function __construct ( UserService $userService )
  {
    $this -> userService = $userService;
  }

  /**
   * @Rest\Get("/users")
   * @return Response
   */
  public function getAllUsers () : Response
  {
    $users = $this -> userService -> getAll ();
    $view = $this -> view ( $users, Response::HTTP_OK );
    return $this -> handleView ( $view );
  }
}
