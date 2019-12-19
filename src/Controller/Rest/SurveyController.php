<?php

namespace App\Controller\Rest;

use App\Entity\Survey;
use App\Form\Type\SurveyType;
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
    public function getBusinessSurveys(Request $request): View
    {
        $content = json_decode($request->getContent(), true);
        $id = $content['business_id'];
        $checker = $this->checkerService->businessChecker($id);

        $surveys= array();
        if ($checker['status']) {
            $business = $checker['business'];
            $surveys = $this->surveyService->getBusinessSurveys($business);
        }
        
        return View::create($surveys, Response::HTTP_CREATED);
    }

    /**
     * @Rest\Post("/api/survey/create")
     */
    public function createNewSurvey(Request $request): View
    {
        $content = json_decode($request->getContent(), true);
        $name = $content['name'];
        $description = $content['description'];
        $format = $content['format'];
        $business_id = $content['business_id'];
 
        $checker = $this->checkerService->businessChecker($business_id);

        $result = array();
        if ($checker['status']) {
            $business = $checker['business'];
            $result = $this->surveyService->createSurvey($name, $description, $format, $business);
        }
    
        return View::create($result, Response::HTTP_CREATED);
    }


    // /**
    //  * @Rest\Post("/api/survey/create")
    //  */
    // public function createNewSurvey(Request $request): View
    // {

    //     $survey = new Survey();

    //     $form = $this->createForm(SurveyType::class, $survey);


    //     $result = ['success' => 0];
    //     $form->handleRequest($request);
    //     if ($form->isSubmitted() && $form->isValid()) {
    //         // $form->getData() holds the submitted values
    //         // but, the original `$task` variable has also been updated
    //         $survey = $form->getData();

    //         // ... perform some action, such as saving the task to the database
    //         // for example, if Task is a Doctrine entity, save it!
    //          $entityManager = $this->getDoctrine()->getManager();
    //          $entityManager->persist($survey);
    //          $entityManager->flush();

    //          $result = ['success' => 1];
    //     }
    
    //     return View::create($result, Response::HTTP_CREATED);
    // }
    
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
        $business_id = $content['business_id'];

        $checker = $this->checkerService->businessChecker($business_id);

        $result = array();
        if ($checker['status']) {
            $business = $checker['business'];
            $result = $this->surveyService->editSurvey($id, $name, $description, $format, $business);
        }
    
        return View::create($result, Response::HTTP_CREATED);
    }

    /**
     * @Rest\Get("api/survey/show")
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

    // /**
    //  * @Rest\Get("/survey/get_branch_surveys")
    //  */
    // public function getBranchSurveys(Request $request): View
    // {
    //     $content = json_decode($request->getContent(), true);
    //     $id = $content['branch_id'];
    //     $checker = $this->checkerService->branchChecker($id);

    //     $surveys= array();
    //     if ($checker['status']) {
    //         $branch = $checker['branch'];
    //         $surveys = $this->surveyService->getBranchSurveys($branch);
    //     }

    //     return View::create($surveys, Response::HTTP_CREATED);
    // }
   
}