<?php

namespace App\Service;

use App\Entity\Business;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityNotFoundException;

class BusinessService
{ 
    
    private $businessRepository;
    
    private $entityManager;

    private $passwordEncoder;
    
    public function __construct(EntityManagerInterface $entityManager) {
        $this->businessRepository = $entityManager->getRepository(Business::class);
        $this->entityManager = $entityManager;
    }

    /**
     * Get Business Object
     */
    public function getBusinessObject(int $id)
    { 
        $business = $this->businessRepository->findOneBy([
            'id' => $id
        ]);

        return $business;
    }


    /**
     * Fetch a list of all businesses
     */
    public function getAllBusinesses()
    { 
        $result = $this->businessRepository->findAll();

        $businesses = array();
        foreach ($result as $oneBusiness) {

            $businesses[] = [
               'id' => $oneBusiness->getId(),
               'name' => $oneBusiness->getName()
            ];
        }

        return json_encode($businesses);
    }

    // /**
    //  * Used for sign up
    //  */
    // public function registerMethod($email, $password)
    // {
    //     $return = array();
    //      if ($email && $password) {
    
    //         $userExists = $this->usersRepository->findOneBy([
    //             'email' => $email
    //         ]);

    //         if($userExists) { 
    //             $return = array('message' => 'User already exists');
    //         } else {
    //             $user = new User();
    //             $user->setEmail($email);
    //             $user->setPassword($this->passwordEncoder->encodePassword( $user, $password ));
    //             $user->setToken(md5(uniqid(rand(), true)));
    //             $user->setRoles(['ROLE_USER']);

    //             $this->entityManager->persist($user);
    //             $this->entityManager->flush();

    //             $return = array('success' => 1);   
    //         }

    //         return $return;

    //      } else {
    //         $return = array('success' => 0);
    //         return $return;
    //     }

    //     echo json_encode($return);      
    // }


}