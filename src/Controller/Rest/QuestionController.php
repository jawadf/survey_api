<?php

namespace App\Controller\Rest;

use App\Service\CheckerService;
use App\Service\QuestionService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\View\View;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityNotFoundException;

class QuestionController extends FOSRestController
{
    private $questionService;

    private $checkerService;

    public function __construct(QuestionService $questionService, CheckerService $checkerService)
    {
        $this->questionService = $questionService;
        $this->checkerService = $checkerService;
    }

    /**
     * @Rest\Post("/survey/question/create")
     */
    public function createNewQuestion(Request $request): View
    {
        $content = json_decode($request->getContent(), true);

        $title = $content['title'];
        $survey_id = $content['survey_id'];
        $answer_type = $content['answer_type'];
        $answers = $content['answers'];

        $checker = $this->checkerService->surveyChecker($survey_id);

        $result= array();
        if ($checker['status']) {
            $survey = $checker['survey'];
            $result = $this->questionService->createQuestion($title, $survey, $answer_type, $answers);
        }
        
        return View::create($result, Response::HTTP_CREATED);
    }

    /**
     * @Rest\Get("/survey/question")
     */
    public function getSurveyQuestions(Request $request): View
    {
        $content = json_decode($request->getContent(), true);
        $id = $content['survey_id'];

        $checker = $this->checkerService->surveyChecker($id);

        $questions= array();
        $result= array();
        if ($checker['status']) {
            $survey = $checker['survey'];
            $questions = $this->questionService->getSurveyQuestions($survey);
        }
        
        return View::create($questions, Response::HTTP_CREATED);
    }

    /**
     * @Rest\Put("/survey/question/edit")
     */
    public function editQuestion(Request $request): View
    {
        $content = json_decode($request->getContent(), true);

        $id= $content['question_id'];
        $title = $content['title'];
        $survey_id = $content['survey_id'];
        $answer_type = $content['answer_type'];
        $answers = $content['answers'];

        $checker = $this->checkerService->surveyChecker($survey_id);

        $result= array();
        if ($checker['status']) {
            $survey = $checker['survey'];
            $result = $this->questionService->edit($id, $title, $survey, $answer_type, $answers);
        }
        
        return View::create($result, Response::HTTP_CREATED);
    }

    /**
     * @Rest\Get("/survey/question/show")
     */
    public function fetchOneQuestion(Request $request): View
    {
        $content = json_decode($request->getContent(), true);
        $id= $content['id'];
        $result = $this->questionService->fetchQuestion($id);

        return View::create($result, Response::HTTP_CREATED);
    }

    /** 
     * @Rest\Delete("/survey/question/delete")
     */
    public function deleteQuestion(Request $request): View
    {
        $content = json_decode($request->getContent(), true);
        $id= $content['id'];
        $result = $this->questionService->delete($id);

        return View::create($result, Response::HTTP_CREATED);
    }
}