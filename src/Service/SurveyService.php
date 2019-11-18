<?php

namespace App\Service; 

use App\Entity\Survey;
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
    public function createSurvey(string $name, string $description, string $format)
    {
        $return = array();
        if($name && $format) {
            $survey = new Survey();
            $survey->setName($name);
            $survey->setFormat($format);
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
                'format' => $survey->getFormat()
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
     * GET ALL THE SURVEYS OF A SPECIFIC USER
     */
    public function getUserSurveys($user)
    {

        $result = $this->surveyRepository->findBy(
            ['user' => $user]
        );

        $surveys = array();
        foreach ($result as $oneSurvey) {
            $surveys[] = [
               'id' => $oneSurvey->getId(),
               'name' => $oneSurvey->getName(),
               'description' => $oneSurvey->getDescription(),
               'format' => $oneSurvey->getFormat(),
               'user_id' => $oneSurvey->getUser()->getId(),
            ];
        }

        echo json_encode($surveys);
    }


}