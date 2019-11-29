<?php

namespace App\Service;  

use App\Entity\User;
use App\Entity\Survey;
use App\Entity\Branch;
use Doctrine\ORM\EntityManagerInterface;

class CheckerService
{    
    
    private $usersRepository;
    private $surveyRepository;
    private $branchRepository;
    
    public function __construct(EntityManagerInterface $entityManager) {
        $this->usersRepository = $entityManager->getRepository(User::class);
        $this->surveyRepository = $entityManager->getRepository(Survey::class);
        $this->branchRepository = $entityManager->getRepository(Branch::class);
    }

    /**
     *  Checks if there's a User with this id
     */
    public function userChecker(int $id)  
    {
        $user = $this->usersRepository->findOneBy([
            'id' => $id
        ]);
                
        if ($user) {
            return array('status' => true, 'user' => $user );
        } else {
            return array( 'status' => false );
        }  
    }

    public function surveyChecker(int $id)
    {
        $survey = $this->surveyRepository->findOneBy([
            'id' => $id
        ]);
                
        if ($survey) {
            return array('status' => true, 'survey' => $survey );
        } else {
            return array( 'status' => false );
        } 
    }

    public function branchChecker(int $id)
    {
        $branch = $this->branchRepository->findOneBy([
            'id' => $id
        ]);
                
        if ($branch) {
            return array('status' => true, 'branch' => $branch );
        } else {
            return array( 'status' => false );
        } 
    }

}