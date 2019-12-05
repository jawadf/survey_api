<?php

/*******************************************************************
 * 
 *                            LEGACY
 *
 *******************************************************************/

namespace App\Controller\Rest;

 use App\Service\SurveyService;
 use App\Service\QuestionService;
 use App\Service\BranchService;
 use App\Service\UserService;
 use App\Service\CheckerService;
 use Symfony\Component\HttpFoundation\Request;
 use Symfony\Component\HttpFoundation\Response;
 use FOS\RestBundle\Controller\Annotations as Rest;
 use FOS\RestBundle\Controller\FOSRestController;
 use FOS\RestBundle\View\View;

 class AdminApiController extends FOSRestController
 {

//     private $surveyService;
//     private $questionService;
//     private $branchService;
//     private $userService;
//     private $checkerService;

//     public function __construct(SurveyService $surveyService, QuestionService $questionService, BranchService $branchService, UserService $userService, CheckerService $checkerService)
//     {
//         $this->surveyService = $surveyService;
//         $this->questionService = $questionService;
//         $this->branchService = $branchService;
//         $this->userService = $userService;
//         $this->checkerService = $checkerService;
//     }

//     /**************************************************
//      *                 
//      *                ADMIN-ONLY METHODS
//      * 
//      **************************************************/

//     /**
//     * @Rest\Get("/admin")
//     */
//     public function getAdmin(Request $request): View
//     {
//         $result= array('message' => 'Welcome to the admin panel!');
            
//         return View::create($result, Response::HTTP_CREATED);
//     }

//     /**************************************************
//      *                 
//      *                USER METHODS
//      * 
//      **************************************************/

//     /**
//      * @Rest\Post("/admin/register")
//      */ 
//     public function registerHandler(Request $request): View
//     {
//         $content = json_decode($request->getContent(), true);
//         $email = $content['email'];
//         $password = $content['password'];

//         $result = $this->userService->registerMethod($email, $password);

//         return View::create($result, Response::HTTP_CREATED);
//     }

//     /**
//      * @Rest\Post("/admin/login")
//      */
//     public function login(Request $request): View
//     {
//         $result = ['success' => 1];

//         return View::create($result, Response::HTTP_CREATED);
//     }

//     /**************************************************
//      *                 
//      *               SURVEY METHODS
//      * 
//      **************************************************/

//     /**
//      * @Rest\Post("/admin/survey/create")
//     */
//     public function createNewSurvey(Request $request): View
//     {
//         $content = json_decode($request->getContent(), true);
//         $name = $content['name'];
//         $description = $content['description'];
//         $format = $content['format'];
//         $user_id = $content['user_id'];
 
//         $checker = $this->checkerService->userChecker($user_id);

//         $result = array();
//         if ($checker['status']) {
//             $user = $checker['user'];
//             $result = $this->surveyService->createSurvey($name, $description, $format, $user);
//         }
    
//         return View::create($result, Response::HTTP_CREATED);
//     }

//     /**
//      * @Rest\Get("/admin/survey/getUserSurveys")
//      */
//     public function getUserSurveys(Request $request): View
//     {
//         $content = json_decode($request->getContent(), true);
//         $id = $content['user_id'];
//         $checker = $this->checkerService->userChecker($id);

//         $surveys= array();
//         if ($checker['status']) {
//             $user = $checker['user'];
//             $surveys = $this->surveyService->getUserSurveys($user);
//         }
        
//         return View::create($surveys, Response::HTTP_CREATED);
//     }


//     /**
//      * @Rest\Put("/admin/survey/edit")
//      */
//     public function editSurvey(Request $request): View
//     {
//         $content = json_decode($request->getContent(), true);
//         $id = $content['id'];
//         $name = $content['name'];
//         $description = $content['description'];
//         $format = $content['format'];
//         $user_id = $content['user_id'];

//         $checker = $this->checkerService->userChecker($user_id);

//         $result = array();
//         if ($checker['status']) {
//             $user = $checker['user'];
//             $result = $this->surveyService->editSurvey($id, $name, $description, $format, $user);
//         }
    
//         return View::create($result, Response::HTTP_CREATED);
//     }

//     /**
//      * @Rest\Get("admin/survey/show")
//      */
//     public function fetchOneSurvey(Request $request): View
//     {
//         $content = json_decode($request->getContent(), true);
//         $id = $content['id'];
//         $result = $this->surveyService->fetchSurvey($id);

//         return View::create($result, Response::HTTP_CREATED);
//     }

//     /**
//      * @Rest\Delete("admin/survey/delete")
//      */
//     public function deleteSurvey(Request $request): View
//     {
//         $content = json_decode($request->getContent(), true);
//         $id = $content['id'];
//         $result = $this->surveyService->deleteSurvey($id);

//         return View::create($result, Response::HTTP_CREATED);
//     }


//     /**************************************************
//      *                 
//      *               QUESTION METHODS
//      * 
//      **************************************************/


//     /**
//      * @Rest\Post("/admin/survey/question/create")
//      */
//     public function createNewQuestion(Request $request): View
//     {
//         $content = json_decode($request->getContent(), true);

//         $title = $content['title'];
//         $survey_id = $content['survey_id'];
//         $answer_type = $content['answer_type'];
//         $answers = $content['answers'];

//         $checker = $this->checkerService->surveyChecker($survey_id);

//         $result= array();
//         if ($checker['status']) {
//             $survey = $checker['survey'];
//             $result = $this->questionService->createQuestion($title, $survey, $answer_type, $answers);
//         }
        
//         return View::create($result, Response::HTTP_CREATED);
//     }

//     /**
//      * @Rest\Get("/admin/survey/question")
//      */
//     public function getSurveyQuestions(Request $request): View
//     {
//         $content = json_decode($request->getContent(), true);
//         $id = $content['survey_id'];

//         $checker = $this->checkerService->surveyChecker($id);

//         $questions= array();
//         $result= array();
//         if ($checker['status']) {
//             $survey = $checker['survey'];
//             $questions = $this->questionService->getSurveyQuestions($survey);
//         }
        
//         return View::create($questions, Response::HTTP_CREATED);
//     }

//     /**
//      * @Rest\Put("/admin/survey/question/edit")
//      */
//     public function editQuestion(Request $request): View
//     {
//         $content = json_decode($request->getContent(), true);

//         $id= $content['question_id'];
//         $title = $content['title'];
//         $survey_id = $content['survey_id'];
//         $answer_type = $content['answer_type'];
//         $answers = $content['answers'];

//         $checker = $this->checkerService->surveyChecker($survey_id);

//         $result= array();
//         if ($checker['status']) {
//             $survey = $checker['survey'];
//             $result = $this->questionService->edit($id, $title, $survey, $answer_type, $answers);
//         }
        
//         return View::create($result, Response::HTTP_CREATED);
//     }

//     /** 
//      * @Rest\Get("/admin/survey/question/show")
//      */
//     public function fetchOneQuestion(Request $request): View
//     {
//         $content = json_decode($request->getContent(), true);
//         $id= $content['id'];
//         $result = $this->questionService->fetchQuestion($id);

//         return View::create($result, Response::HTTP_CREATED);
//     }

//     /** 
//      * @Rest\Delete("/admin/survey/question/delete")
//      */
//     public function deleteQuestion(Request $request): View
//     {
//         $content = json_decode($request->getContent(), true);
//         $id= $content['id'];
//         $result = $this->questionService->delete($id);

//         return View::create($result, Response::HTTP_CREATED);
//     }


//     /**************************************************
//      *                 
//      *               BRANCH METHODS
//      * 
//      **************************************************/


//     /**
//      * @Rest\Post("/admin/survey/branch/create")
//      */
//     public function createBranch(Request $request): View
//     {
//         $content = json_decode($request->getContent(), true);
//         $name = $content['name'];
//         $user_id = $content['user_id'];

//         $checker = $this->checkerService->userChecker($user_id);

//         $result= array();
//         if ($checker['status']) {
//             $user = $checker['user'];
//             $result = $this->branchService->createBranch($name, $user);
//         }
    
//         return View::create($result, Response::HTTP_CREATED);
//     }

//     /**
//      * @Rest\Post("/admin/survey/branch/add")
//      */
//     public function addSurveyToBranch(Request $request): View
//     {
//         $content = json_decode($request->getContent(), true);
//         $branch_id = $content['branch_id'];
//         $survey_id = $content['survey_id'];

//         $result = $this->branchService->addSurvey($branch_id, $survey_id);
    
//         return View::create($result, Response::HTTP_CREATED);
//     }

//     /**
//      * @Rest\Delete("/admin/survey/branch/remove")
//      */
//     public function removeSurveyFromBranch(Request $request): View
//     {
//         $content = json_decode($request->getContent(), true);
//         $branch_id = $content['branch_id'];
//         $survey_id = $content['survey_id'];

//         $result = $this->branchService->removeSurvey($branch_id, $survey_id);
    
//         return View::create($result, Response::HTTP_CREATED);
//     }

//     /**
//      * @Rest\Put("/admin/survey/branch/edit")
//      */
//     public function editBranch(Request $request): View
//     {
//         $content = json_decode($request->getContent(), true);
//         $id = $content['id'];
//         $name = $content['name'];
//         $user_id = $content['user_id'];

//         $checker = $this->checkerService->userChecker($user_id);

//         $result= array();
//         if ($checker['status']) {
//             $user = $checker['user'];
//             $result = $this->branchService->editBranch($id, $name, $user);
//         }
    
//         return View::create($result, Response::HTTP_CREATED);
//     }

//     /**
//      * @Rest\Get("/admin/survey/branch")
//      */
//     public function getUserBranches(Request $request): View
//     {
//         $content = json_decode($request->getContent(), true);
//         $id = $content['user_id'];
//         $checker = $this->checkerService->userChecker($id);

//         $branches= array();
//         if ($checker['status']) {
//             $user = $checker['user'];
//             $branches = $this->branchService->getUserbranches($user);
//         }
        
//         return View::create($branches, Response::HTTP_CREATED);
//     }

//     /**
//      * @Rest\Delete("/admin/survey/branch/delete")
//      */
//     public function deleteBranch(Request $request): View
//     {
//         $content = json_decode($request->getContent(), true);
//         $id = $content['id'];
//         $result = $this->branchService->deleteBranch($id);
        
//         return View::create($result, Response::HTTP_CREATED);
//     }

 }