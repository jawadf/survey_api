<?php

namespace App\Controller\Web;

use App\Entity\Survey;
use App\Entity\Question;
use App\Service\SurveyService;
use App\Service\UserService;
use App\Form\Type\SurveyType;
use App\Form\Type\DeleteType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class AdminController extends AbstractController
{

    private $surveyService;
    private $userService;

    public function __construct(SurveyService $surveyService, UserService $userService)
    {
        $this->surveyService = $surveyService;
        $this->userService = $userService;
    }

    /**
      * @Route("/admin", name="admin_panel")
      */
    public function index()
    {
        return $this->render('admin/base.html.twig');
    }

      /**************************************************
      *                 
      *               SURVEY METHODS
      * 
      **************************************************/

    /**
      * @Route("/admin/survey", name="admin_surveys")
      */
    public function surveyPage()
    {
        $allSurveys  = $this->surveyService->getAllSurveys();
        $allUsers  = $this->userService->getAllUsers();

        return $this->render('admin/pages/surveys.html.twig', [
            'all_surveys' => $allSurveys,
            'all_users' => $allUsers
        ]);
    }

    /**
      * @Route("/admin/survey/create", name="admin_create_survey")
      */
      public function createNewSurvey(Request $request)
      {
          $survey = new Survey();
          $newQuestion = new Question();
          $survey->addQuestion($newQuestion);

          $form = $this->createForm(SurveyType::class, $survey);
     
          $form->handleRequest($request);
          if ($form->isSubmitted() && $form->isValid()) {
              $survey= $form->getData();

              $questions = $survey->getQuestions();
              foreach ($questions as $question) {
                $question->setSurvey($survey);
              }
  
              $entityManager = $this->getDoctrine()->getManager();
              $entityManager->persist($survey);
              $entityManager->flush();
  
              return $this->redirectToRoute('admin_surveys');
          }
      
          return $this->render('admin/pages/create_survey.html.twig', [
              'form' => $form->createView()
          ]);
      }
  
      /**
        * @Route("/admin/survey/{id}/edit", name="admin_edit_survey")
        */
        public function editSurvey(Survey $survey, Request $request, $id)
        {
            $entityManager = $this->getDoctrine()->getManager();
            if (null === $survey = $entityManager->getRepository(Survey::class)->find($id)) {
                throw $this->createNotFoundException('No survey found for id '.$id);
            }
  
            $form = $this->createForm(SurveyType::class, $survey);
  
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
  
            // remove the relationship between the Question and the Survey
            //  foreach ($originalImages as $image) {
            //   if (false === $post->getImage()->contains($image)) {
            //       // remove the Task from the Tag
            //       //$image->getPost()->removeImage($post);
            //       // if it was a many-to-one relationship, remove the relationship like this
            //       // $image->setPost(null);
            //       $entityManager->persist($image);
            //       // if you wanted to delete the Tag entirely, you can also do that
            //       $entityManager->remove($image);
            //       $imagesDirectory = $this->getParameter('images_directory');
            //       unlink($imagesDirectory.'/'.$image->getFilename());
            //   }

            $survey= $form->getData();

              $questions = $survey->getQuestions();
              foreach ($questions as $question) {
                $question->setSurvey($survey);
              }
              
              if($questions) {
                  // Upload the image using a Service, check notes in ImagesUploader.php 
                 // $imagesUploader->upload($request, $images);
              }
  
             // $post = $form->getData();
              $entityManager->persist($survey);
              $entityManager->flush();
              return $this->redirectToRoute('admin_surveys');
        
            } 
  
          return $this->render('admin/pages/edit_survey.html.twig', [
              'form' => $form->createView()
          ]);
  
        }

    /**
      * @Route("/admin/survey/{id}/delete", name="admin_delete_survey")
      */
      public function deleteSurvey($id)
      {
        $allSurveys  = $this->surveyService->getAllSurveys();
        $allUsers  = $this->userService->getAllUsers();
    
        $entityManager = $this->getDoctrine()->getManager();
        $surveyRepository = $entityManager->getRepository(Survey::class);

        /***********  To tackle foreign key constraints ************/
        $questionsRepository = $entityManager->getRepository(Question::class);
        $survey = $surveyRepository->findOneBy([
          'id' => $id
        ]);
        $questions = $questionsRepository->findBy([
          'survey' => $survey
        ]);
        foreach ($questions as $question) {
          $entityManager->remove($question);
        }
        /**********************************************************/

        $entityManager->remove($survey);
        $entityManager->flush();

        return $this->redirectToRoute('admin_surveys');
      }

    /**
     * @Route("/admin/survey/user/{id}", name="admin_user_surveys")
     */
    public function manageUserSurveys($id)
    {
        $user = $this->userService->getUserObject($id);
        $userSurveys  = $this->surveyService->getUserSurveys($user);

        dump($userSurveys);

        return $this->render('admin/pages/user_surveys.html.twig', [
            'user_surveys' => $userSurveys,
            'user_id' => $id
        ]);
    }

    /**************************************************
      *                 
      *                USER METHODS
      * 
      **************************************************/

    /**
      * @Route("/admin/user", name="admin_users")
      */
    public function usersPage() 
    {
      $allUsers  = $this->userService->getAllUsers();

      return $this->render('admin/pages/users.html.twig', [
        'all_users' => $allUsers
      ]);
    }


    /**
      * @Route("/admin/user/create", name="admin_create_user")
      */
      public function addUser(Request $request)
      {
          // $survey = new Survey();
  
          // $newQuestion = new Question();
          // $newQuestion->setSurvey($survey);
          // $survey->getQuestions()->add($newQuestion);
  
          // $form = $this->createForm(SurveyType::class, $survey);
          // dump($form);
     
          // $form->handleRequest($request);
          // if ($form->isSubmitted() && $form->isValid()) {
          //     $data= $form->getData();
  
          //     $entityManager = $this->getDoctrine()->getManager();
          //     $entityManager->persist($survey);
          //     $entityManager->flush();
  
  
          //     return $this->redirectToRoute('admin_surveys');
          // }
      
          return $this->render('admin/pages/create_user.html.twig'
          // , [
          //     'form' => $form->createView()
          // ]
        );
      }

    /**
      * @Route("/admin/user/edit", name="admin_edit_user")
      */
      public function editUser(Request $request)
      {
          return $this->render('admin/pages/edit_user.html.twig');
      }
  

    /**
      * @Route("/admin/test")
      */
    public function testPage()
    {
        return $this->render('admin/pages/test.html.twig');
    }


}
