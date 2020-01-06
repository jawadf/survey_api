<?php

namespace App\Controller\Rest;

use App\Service\CheckerService;
use App\Service\BusinessService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Controller\Annotations\QueryParam;
use FOS\RestBundle\Request\ParamFetcher;
use FOS\RestBundle\View\View;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityNotFoundException;


class BusinessController extends FOSRestController
{
    private $businessService;

    private $checkerService;

    public function __construct(BusinessService $businessService, CheckerService $checkerService)
    {
        $this->businessService = $businessService;
        $this->checkerService = $checkerService;
    }

    /**
     * 
     * This method is intended for the 'Autocomplete' functionality, in the 'Create Survey' section
     * 
     * @QueryParam(name="q", nullable=true)
     * 
     * @Rest\Get("/api/business/names")
     */
    public function getBusinessesNames(Request $request, EntityManagerInterface $entityManager, ParamFetcher $paramFetcher): View
    {
        $q = $paramFetcher->get('q');

        $query = $entityManager->createQuery(
            'SELECT business.name FROM  App\Entity\Business business WHERE business.name LIKE :q '
          )
          ->setParameter('q', '%'.$q.'%')
          ;

        $result = array();

        foreach ($query->getResult() as $obj) {
            $result[] = $obj['name'];
        }

        return View::create($result, Response::HTTP_CREATED);
    }


    /** 
     * This method is also used in the 'Autocomplete' functionality
     * It recieves a name parameter, and returns the Business Entity with this name
     * 
     *  @QueryParam(name="name", nullable=true)
     * 
     * @Rest\Get("/api/business/findbusiness")
     */
    public function findBusinessByName(Request $request, EntityManagerInterface $entityManager, ParamFetcher $paramFetcher): View
    {
        $name = $paramFetcher->get('name');

        $query = $entityManager->createQuery(
            'SELECT business FROM  App\Entity\Business business WHERE business.name = :parameter '
          )
          ->setParameter('parameter', $name)
          ;

        $result = ['business' => $query->getResult()];


        return View::create($result, Response::HTTP_CREATED);
    }

}