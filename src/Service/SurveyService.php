<?php

namespace App\Service; 

use App\Entity\Survey;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityNotFoundException;

class SurveyService
{
    private $surveyRepository;
    private $entityManager;
 
    public function __construct(EntityManagerInterface $entityManager) 
    {
        $this->surveyRepository = $entityManager->getRepository(Survey::class);
        $this->entityManager = $entityManager;
    } 

    /**
     * CREATE SURVEY
     * 
     * Acceptable formats: 'one-question-per-screen', 'all-questions-one-screen'
     */
    public function createSurvey(string $name, string $description, string $format, User $user)
    {
        $return = array();
        if($name && $format) {
            $survey = new Survey();
            $survey->setName($name);
            $survey->setFormat($format);
            $survey->setUser($user);
            if($description != '0') {
                $survey->setDescription($description);
            } else {
                $survey->setDescription(null);
            }

            $this->entityManager->persist($survey);
            $this->entityManager->flush();

            $return = [
                'success' => 1,
                'message' => 'Survey successfully created!',
                'name' => $survey->getName(),
                'description' => $survey->getDescription(),
                'format' => $survey->getFormat(),
                'user_id' => $survey->getUser()->getId()
                ];
        } else {
            $return = [
                'success' => 0,
                'message' => 'Error: could not create survey!'
            ];
        }

        return $return;
    }

    /**
     * GET ALL THE SURVEYS OF ALL USERS
     */
    public function getAllSurveys()
    { 
        $result = $this->surveyRepository->findAll();

        $surveys = array();
        foreach ($result as $oneSurvey) {

            $questions = $oneSurvey->getQuestions();
            $branches = array();

            foreach ( $oneSurvey->getBranches() as $branch) {
                $branches[] = $branch->getName();
            }

            $surveys[] = [
               'id' => $oneSurvey->getId(),
               'name' => $oneSurvey->getName(),
               'description' => $oneSurvey->getDescription(),
               'format' => $oneSurvey->getFormat(),
               'user_id' => $oneSurvey->getUser()->getId(),
               'questions' => sizeof($questions),
               'branches' => $branches
            ];
        }

        return json_encode($surveys); 
    }


    /**
     * GET ALL THE SURVEYS OF A SPECIFIC USER
     */
    public function getUserSurveys($user)
    { 
        $result = $this->surveyRepository->findBy(
            ['user' => $user]
        );

        $surveys = array();
        foreach ($result as $oneSurvey) {

            $questions = $oneSurvey->getQuestions();
            $surveys[] = [
               'id' => $oneSurvey->getId(),
               'name' => $oneSurvey->getName(),
               'description' => $oneSurvey->getDescription(),
               'format' => $oneSurvey->getFormat(),
               'user_id' => $oneSurvey->getUser()->getId(),
               'questions' => sizeof($questions)
            ];
        }

        dump($surveys);

        return json_encode($surveys);
    }

    // /**
    //  * GET ALL THE SURVEYS OF A SPECIFIC BRANCH
    //  */
    // public function getBranchSurveys($branch)
    // { 

    //     $result = $this->surveyRepository->findBy(
    //         ['branches' => $branch]
    //     );

    //     $surveys = array();
    //     foreach ($result as $oneSurvey) {
    //         $surveys[] = [
    //            'id' => $oneSurvey->getId(),
    //            'name' => $oneSurvey->getName(),
    //            'description' => $oneSurvey->getDescription(),
    //            'format' => $oneSurvey->getFormat()
    //         ];
    //     }

    //     echo json_encode($surveys);
    // }


    /**
     * EDIT A SURVEY
     */
    public function editSurvey(int $id, string $name, string $description, string $format, User $user)
    {
        $survey = $this->surveyRepository->find($id);
        $return = array();
        if($survey) {
            if($name && $description && $format && $user) {
           
                $survey->setName($name);
                $survey->setDescription($description);
                $survey->setFormat($format);
                $survey->setUser($user);

                $this->entityManager->persist($survey);
                $this->entityManager->flush();

                $return = [
                    'success' => 1,
                    'message' => 'Survey successfully edited!',
                    'id' => $survey->getId(),
                    'name' => $survey->getName(),
                    'description' => $survey->getDescription(),
                    'format' => $survey->getFormat(),
                    'user_id' => $survey->getUser()->getId()
                    ];
            } else {
                $return = [
                    'success' => 0,
                    'message' => 'Error: could not edit survey! Please specify all the required fields'
                ];
            }
        }
        return $return;
    }

    /**
     * FETCH A SURVEY
     */
    public function fetchSurvey(int $id)
    {
        $survey = $this->surveyRepository->find($id);

        $return = array();
        if ($survey) {
            $return[] = [
                'success' => 1,
                'id' => $survey->getId(),
                'name' => $survey->getName(),
                'description' => $survey->getDescription(),
                'format' => $survey->getFormat(),
                'user_id' => $survey->getUser()->getId()
            ];
        } else {
            $return[] = [
                'success' => 0
            ];
        }
        echo json_encode($return);
    }

    /**
     * DELETE A SURVEY
     */
    public function deleteSurvey(int $id)
    {
        $survey = $this->surveyRepository->find($id);

        $return = array();
        if ($survey) {
            $this->entityManager->remove($survey); 
            $this->entityManager->flush();
            $return[] = [
                'success' => 1,
                'message' => 'Survey successfully deleted!'
            ];
        } else {
            $return[] = [
                'success' => 0,
                'message' => 'Error: Could not delete survey!'
            ];
        }
        echo json_encode($return);

    }


}