<?php

namespace App\Controller\Rest;

use App\Service\SurveyService;
use App\Service\CheckerService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\View\View;

class SurveyController extends FOSRestController
{
    private $surveyService;

    private $checkerService;

    public function __construct(SurveyService $surveyService, CheckerService $checkerService)
    {
        $this->surveyService = $surveyService;
        $this->checkerService = $checkerService;
    }

    /**
     * @Rest\Get("/survey")
     */
    public function getUserSurveys(Request $request): View
    {
        $id = $request->get('user_id');

        $checker = $this->checkerService->userChecker($id);

        $surveys= array();
        if ($checker['status']) {
            $user = $checker['user'];
            $surveys = $this->surveyService->getUserSurveys($user);
        }
        
        return View::create($surveys, Response::HTTP_CREATED);
    }

    /**
     * @Rest\Post("/survey")
     */
    public function createNewSurvey(Request $request): View
    {
        $name = $request->get('name');
        $description = $request->get('description');
        $format = $request->get('format');

        $result = $this->surveyService->createSurvey($name, $description, $format);
        
        return View::create($result, Response::HTTP_CREATED);
    }
    
    /**
     * Edit
     */
   
}