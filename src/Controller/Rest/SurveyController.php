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
        $content = json_decode($request->getContent(), true);
        $id = $content['user_id'];
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
        $content = json_decode($request->getContent(), true);
        $name = $content['name'];
        $description = $content['description'];
        $format = $content['format'];
        $user_id = $content['user_id'];

        $checker = $this->checkerService->userChecker($user_id);

        $result = array();
        if ($checker['status']) {
            $user = $checker['user'];
            $result = $this->surveyService->createSurvey($name, $description, $format, $user);
        }
    
        return View::create($result, Response::HTTP_CREATED);
    }
    
    /**
     * @Rest\Put("/survey/edit")
     */
    public function editSurvey(Request $request): View
    {
        $content = json_decode($request->getContent(), true);
        $id = $content['id'];
        $name = $content['name'];
        $description = $content['description'];
        $format = $content['format'];
        $user_id = $content['user_id'];

        $checker = $this->checkerService->userChecker($user_id);

        $result = array();
        if ($checker['status']) {
            $user = $checker['user'];
            $result = $this->surveyService->editSurvey($id, $name, $description, $format, $user);
        }
    
        return View::create($result, Response::HTTP_CREATED);
    }

    /**
     * @Rest\Get("/survey/show")
     */
    public function fetchOneSurvey(Request $request): View
    {
        $content = json_decode($request->getContent(), true);
        $id = $content['id'];
        $result = $this->surveyService->fetchSurvey($id);

        return View::create($result, Response::HTTP_CREATED);
    }

    /**
     * @Rest\Delete("/survey/delete")
     */
    public function deleteSurvey(Request $request): View
    {
        $content = json_decode($request->getContent(), true);
        $id = $content['id'];
        $result = $this->surveyService->deleteSurvey($id);

        return View::create($result, Response::HTTP_CREATED);
    }
   
}