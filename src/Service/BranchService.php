<?php

namespace App\Service;

use App\Entity\Branch;
use App\Entity\Survey;
use App\Entity\Business;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityNotFoundException;

class BranchService
{
    private $branchRepository;
    private $surveyRepository;
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager) 
    {
        $this->branchRepository = $entityManager->getRepository(Branch::class);
        $this->surveyRepository = $entityManager->getRepository(Survey::class);
        $this->entityManager = $entityManager;
    }

    /**
     * CREATE BRANCH
     */
    public function createBranch(string $name)
    {
        $return = array();
        if($name) {
            // Todo: check if there's already a branch with this business_id AND name
            $branch = new Branch();
            $branch->setName($name);

            $this->entityManager->persist($branch);
            $this->entityManager->flush();

            $return = [
                'success' => 1,
                'message' => 'Branch successfully created!',
                'id' => $branch->getId(),
                'name' => $branch->getName(),
                ];
        } else {
            $return = [
                'success' => 0,
                'message' => 'Error: could not create branch! Please specify all the required fields'
            ];
        }

        echo json_encode($return);
    }

    /**
     * ADD SURVEY TO BRANCH
     */
    public function addSurvey(int $branch_id, int $survey_id)
    {
        $branch = $this->branchRepository->find($branch_id);
        $survey = $this->surveyRepository->find($survey_id);

        $return = array();
        if($branch && $survey) {
            $branch->addSurvey($survey);
            $this->entityManager->persist($branch);
            $this->entityManager->flush();

            $return[] = [
                'success' => 1,
                'message' => 'Successfully added survey with id '.$survey_id.' to branch with id '.$branch_id
            ];
        } else {
            $return[] = [
                'success' => 0,
                'message' => 'Error adding survey!'
            ];
        } 

        echo json_encode($return);
    }


    /**
     * REMOVE SURVEY FROM BRANCH
     */
    public function removeSurvey(int $branch_id, int $survey_id)
    {
        $branch = $this->branchRepository->find($branch_id);
        $survey = $this->surveyRepository->find($survey_id);

        $return = array();
        if($branch && $survey) {
            $branch->removeSurvey($survey);
            $this->entityManager->persist($branch);
            $this->entityManager->flush();

            $return[] = [
                'success' => 1,
                'message' => 'Successfully removed survey with id '.$survey_id.' from branch with id '.$branch_id
            ];
        } else {
            $return[] = [
                'success' => 0,
                'message' => 'Error removing survey!'
            ];
        } 

        echo json_encode($return);
    }


    /**
     * EDIT BRANCH
     */
    public function editBranch(int $id,string $name)
    {
        $branch = $this->branchRepository->find($id);

        if($branch) {
            $return = array();
            if($name) {
                $branch->setName($name);
    
                $this->entityManager->persist($branch);
                $this->entityManager->flush();
    
                $return = [
                    'success' => 1,
                    'message' => 'Branch successfully edited!',
                    'id' => $branch->getId(),
                    'name' => $branch->getName()
                    ];
            } else {
                $return = [
                    'success' => 0,
                    'message' => 'Error: could not edit branch! Please specify all the required fields'
                ];
            }

        }

        echo json_encode($return);
    }

    /**
     * DELETE BRANCH
     */
    public function deleteBranch(int $id)
    {
        $branch = $this->branchRepository->find($id);

        $return = array();
        if ($branch) {
            $this->entityManager->remove($branch); 
            $this->entityManager->flush();
            $return[] = [
                'success' => 1,
                'message' => 'Branch successfully deleted!'
            ]; 
        } else {
            $return[] = [
                'success' => 0,
                'message' => 'Error: Could not delete branch!'
            ];
        }
        echo json_encode($return);
    }


    /**
     * GET ALL THE BRANCHES OF A SPECIFIC Business
     */
    public function getBusinessBranches($business)
    {
        $result = $this->branchRepository->findBy(
            ['business' => $business]
        );
        $branches = array();
        foreach ($result as $oneBranch) {
            $branches[] = [
                'id' => $oneBranch->getId(),
                'name' => $oneBranch->getName(),
                'business_id' => $oneBranch->getBusiness()->getId(),
            ];
        }
        echo json_encode($branches);
    }

}