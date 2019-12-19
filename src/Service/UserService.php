<?php

namespace App\Service;

use App\Entity\Manager;
use App\Entity\Employee;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityNotFoundException;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserService
{ 
    
    private $usersRepository;
    
    // private $entityManager;

    // private $passwordEncoder;
    
     public function __construct(EntityManagerInterface $entityManager, UserPasswordEncoderInterface $passwordEncoder) {
         $this->managersRepository = $entityManager->getRepository(Manager::class);
         $this->employeesRepository = $entityManager->getRepository(Employee::class);
    //     $this->entityManager = $entityManager;
    //     $this->passwordEncoder = $passwordEncoder;
     }

    // /**
    //  * Get User Object
    //  */
    // public function getUserObject(int $id)
    // { 
    //     $user = $this->usersRepository->findOneBy([
    //         'id' => $id
    //     ]);

    //     return $user;
    // }


    /**
     * Fetch a list of all users
     */
    public function getAllUsers()
    { 
        $arrayManagers = $this->managersRepository->findAll();
        $arrayEmployees = $this->employeesRepository->findAll();
        $result = array_merge($arrayManagers, $arrayEmployees);

        $users = array();
        foreach ($result as $oneUser) {

            $users[] = [
               'id' => $oneUser->getId(),
               'email' => $oneUser->getEmail(), 
               'fullname' => $oneUser->getFullname(),
               'roles' => $oneUser->getRoles(),
               
            ];
        }

        return json_encode($users);
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