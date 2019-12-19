<?php

namespace App\Service;  

use App\Entity\Business;
use App\Entity\Survey;
use App\Entity\Branch;
use Doctrine\ORM\EntityManagerInterface;

class CheckerService
{    
    
    private $businessRepository;
    private $surveyRepository;
    private $branchRepository;
    
    public function __construct(EntityManagerInterface $entityManager) {
        $this->businessRepository = $entityManager->getRepository(Business::class);
        $this->surveyRepository = $entityManager->getRepository(Survey::class);
        $this->branchRepository = $entityManager->getRepository(Branch::class);
    }

    /**
     *  Checks if there's a Business with this id
     */
    public function businessChecker(int $id)  
    {
        $business = $this->businessRepository->findOneBy([
            'id' => $id
        ]);
                
        if ($business) {
            return array('status' => true, 'business' => $business );
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