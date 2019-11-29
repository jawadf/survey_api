<?php

namespace App\Service;

use App\Entity\Question;
use App\Entity\Survey;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityNotFoundException;

class QuestionService
{
    private $questionRepository;
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager) 
    {
        $this->questionRepository = $entityManager->getRepository(Question::class);
        $this->entityManager = $entityManager;
    }

    /**
     * CREATE QUESTION
     */
    public function createQuestion(string $title, Survey $survey, string $answer_type, string $answersString)
    {
        $return = array();
        if($title && $answer_type && $answersString) {
            $answersJson = json_decode($answersString); // Convert the string to json
            $question = new Question();
            $question->setTitle($title);
            $question->setSurvey($survey);
            $question->setAnswerType($answer_type);
            $question->setAnswers($answersJson);
            $this->entityManager->persist($question);
            $this->entityManager->flush();
            $return = [
                'success' => 1,
                'message' => 'Question successfully created!',
                'id' => $question->getId(),
                'title' => $question->getTitle(),
                'survey_id' => $question->getSurvey()->getId(),
                'answer_type' => $question->getAnswerType(),
                'answers' => $question->getAnswers()
                ];
        } else {
            $return = [
                'success' => 0,
                'message' => 'Error: could not create question! Please specify all the required fields'
            ];
        }

        return $return;
    }

    /**
     * GET ALL THE QUESTIONS OF A SPECIFIC SURVEY
     */
    public function getSurveyQuestions($survey)
    {
        $result = $this->questionRepository->findBy(
            ['survey' => $survey]
        );
        $questions = array();
        foreach ($result as $oneQuestion) {
            $questions[] = [
                'id' => $oneQuestion->getId(),
                'title' => $oneQuestion->getTitle(),
                'survey_id' => $oneQuestion->getSurvey()->getId(),
                'answer_type' => $oneQuestion->getAnswerType(),
                'answers' => $oneQuestion->getAnswers()
            ];
        }
        echo json_encode($questions);
    }

    /**
     * EDIT CONTENT OF A QUESTION 
     */
    public function edit(int $id, string $title, Survey $survey, string $answer_type, string $answersString)
    {
        $question = $this->questionRepository->find($id);
        $return = array();
        if($question) {
            if($title && $answer_type && $answersString) {
                $answersJson = json_decode($answersString); // Convert the string to json
                $question->setTitle($title);
                $question->setSurvey($survey);
                $question->setAnswerType($answer_type);
                $question->setAnswers($answersJson);
                $this->entityManager->persist($question);
                $this->entityManager->flush();
                $return = [
                    'success' => 1,
                    'message' => 'Question successfully edited!',
                    'id' => $question->getId(),
                    'title' => $question->getTitle(),
                    'survey_id' => $question->getSurvey()->getId(),
                    'answer_type' => $question->getAnswerType(),
                    'answers' => $question->getAnswers()
                    ];
            } else {
                $return = [
                    'success' => 0,
                    'message' => 'Error: could not edit question! Please specify all the required fields'
                ];
            }
        }
        
        return $return;
    }

    /**
     * GET ONE QUESTION 
     */
    public function fetchQuestion($id)
    {
        $question = $this->questionRepository->find($id);

        $return = array();
        if ($question) {
            $return[] = [
                'success' => 1,
                'id' => $question->getId(),
                'title' => $question->getTitle(),
                'survey_id' => $question->getSurvey()->getId(),
                'answer_type' => $question->getAnswerType(),
                'answers' => $question->getAnswers()
            ];
        } else {
            $return[] = [
                'success' => 0
            ];
        }
        echo json_encode($return);
    }


    /**
     * DELETE QUESTION 
     */
    public function delete($id)
    {
        $question = $this->questionRepository->find($id);

        $return = array();
        if ($question) {
            $this->entityManager->remove($question); 
            $this->entityManager->flush();
            $return[] = [
                'success' => 1,
                'message' => 'Question successfully deleted!'
            ];
        } else {
            $return[] = [
                'success' => 0,
                'message' => 'Error: Could not delete question!'
            ];
        }
        echo json_encode($return);
    }
}