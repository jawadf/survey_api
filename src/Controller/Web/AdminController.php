<?php

namespace App\Controller\Web;

use App\Entity\Survey;
use App\Entity\Question;
use App\Entity\User;
use App\Service\SurveyService;
use App\Service\UserService;
use App\Form\Type\SurveyType;
use App\Form\Type\DeleteType;
use App\Repository\UserRepository;
use App\Form\RegistrationFormType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Security\LoginFormAuthenticator;
use Knp\Component\Pager\PaginatorInterface;
use Knp\Bundle\PaginatorBundle\Definition\PaginatorAwareInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Guard\GuardAuthenticatorHandler;

class AdminController extends AbstractController
{
    private $surveyService;
    private $userService;

    public function __construct(SurveyService $surveyService, UserService $userService)
    {
        $this->surveyService = $surveyService;
        $this->userService = $userService;
    }

    // /**
    //   * @Route("/admin", name="admin_panel")
    //   */
    // public function index()
    // {
    //     return $this->render('admin/base.html.twig');
    // }

      /**************************************************
      *                 
      *               SURVEY METHODS
      * 
      **************************************************/

    /**
      * @Route("/admin", name="admin_surveys")
      */
    public function surveyPage(PaginatorInterface $paginator, Request $request)
    {
        //$allSurveys  = $this->surveyService->getAllSurveys();
        //$allUsers  = $this->userService->getAllUsers();

        $entityManager = $this->getDoctrine()->getManager();

        $surveysQuery = $entityManager->createQuery(
          'SELECT survey
          FROM App\Entity\Survey survey'
        );

        $usersQuery = $entityManager->createQuery(
          'SELECT user
          FROM App\Entity\User user'
        );

        $allSurveys = $paginator->paginate(
          $surveysQuery, /* query NOT result */
          $request->query->getInt('page', 1)/*page number*/,
          12/*limit per page*/
        );

        $pagination = $paginator->paginate(
          $usersQuery, /* query NOT result */
          $request->query->getInt('page', 1)/*page number*/,
          8/*limit per page*/
        );

        return $this->render('admin/pages/surveys.html.twig', [
            'all_surveys' => $allSurveys,
            'pagination' => $pagination
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

          //Get users
          $allUsers  = $this->userService->getAllUsers();

          $form = $this->createForm(SurveyType::class, $survey);
     
          $form->handleRequest($request);
          if ($form->isSubmitted() && $form->isValid()) {
              $survey= $form->getData();
              
              $questions = $survey->getQuestions();
              $branches = $survey->getBranches();

              foreach ($questions as $question) {
                $question->setSurvey($survey);
              }
              
              foreach ($branches as $branch) {
                $branch->addSurvey($survey);
              }
  
              $entityManager = $this->getDoctrine()->getManager();
              $entityManager->persist($survey);
              $entityManager->flush();
  
              return $this->redirectToRoute('admin_surveys');
          }
      
          return $this->render('admin/pages/create_survey.html.twig', [
              'form' => $form->createView(),
              'all_users' => $allUsers
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
            $branches = $survey->getBranches();

            foreach ($questions as $question) {
              $question->setSurvey($survey);
            }
            
            foreach ($branches as $branch) {
              $branch->addSurvey($survey);
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
    public function usersPage(PaginatorInterface $paginator, Request $request) 
    {
      $allUsers  = $this->userService->getAllUsers();

      $pagination = $paginator->paginate(
        $allUsers, /* query NOT result */
        $request->query->getInt('page', 1)/*page number*/,
        10/*limit per page*/
      );

      return $this->render('admin/pages/users.html.twig', [
        'pagination' => $pagination
      ]);
    }


    /**
     * @Route("/admin/user/create", name="admin_create_user")
     */
    public function addUser(Request $request, UserPasswordEncoderInterface $passwordEncoder, GuardAuthenticatorHandler $guardHandler, LoginFormAuthenticator $authenticator): Response
    {
        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // encode the plain password
            $user->setPassword(
                $passwordEncoder->encodePassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );
            $user->setRoles(["ROLE_USER"]);
            $user->setToken(md5(uniqid(rand(), true)));

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();

            // do anything else you need here, like send an email

            // return $guardHandler->authenticateUserAndHandleSuccess(
            //     $user,
            //     $request,
            //     $authenticator,
            //     'admin_secured_area' // firewall name in security.yaml
            // );

            return $this->redirectToRoute('admin_create_user');
        }

        return $this->render('admin/pages/create_user.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }

    /**
      * @Route("/admin/user/{id}/edit", name="admin_edit_user")
      */
      public function editUser(Request $request, $id, User $user)
      {

        $entityManager = $this->getDoctrine()->getManager();
        if (null === $user = $entityManager->getRepository(User::class)->find($id)) {
            throw $this->createNotFoundException('No user found for id '.$id);
        }

        $form = $this->createForm(RegistrationFormType::class, $user);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

          $user= $form->getData();

          $entityManager->persist($user);
          $entityManager->flush();
          return $this->redirectToRoute('admin_users');
    
        } 
          return $this->render('admin/pages/edit_user.html.twig', [
            'edituser_form' => $form->createView()
          ]);
      }
  

    /**
      * @Route("/admin/test")
      */
    public function testPage()
    {
        return $this->render('admin/pages/test.html.twig');
    }


}
