<?php

namespace App\Controller\Rest;

use App\Entity\UserAnd;
use App\Service\UserService;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\FOSRestController;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use FOS\RestBundle\View\View;


class UserController extends FOSRestController
{

    private $userService;
   
    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }  

   /**
     * @Rest\Post("/login")
     */
    public function login(Request $request): View
    {
        // $content = json_decode($request->getContent(), true);
        // $email = $content['email'];
        // $password = $content['password']; 

        // $result = $this->userService->loginMethod($email, $password);

        $result = ['success' => 1];

        return View::create($result, Response::HTTP_CREATED);
    }
 

    /**
     * @Rest\Post("/register")
     */ 
    public function registerHandler(Request $request): View
    {
        $content = json_decode($request->getContent(), true);
        $email = $content['email'];
        $password = $content['password'];

        $result = $this->userService->registerMethod($email, $password);

        return View::create($result, Response::HTTP_CREATED);
    }

}