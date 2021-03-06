<?php

namespace App\Controller\Rest;

use App\Entity\Branch;
use App\Service\BranchService;
use App\Service\CheckerService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\View\View;

class BranchController extends FOSRestController
{
    private $branchService;

    private $checkerService;

    public function __construct(BranchService $branchService, CheckerService $checkerService)
    {
        $this->branchService = $branchService;
        $this->checkerService = $checkerService;
    }

    /**
     * @Rest\Post("/survey/branch/create")
     */
    public function createBranch(Request $request): View
    {
        $content = json_decode($request->getContent(), true);
        $name = $content['name'];

        $result = $this->branchService->createBranch($name);
        
        return View::create($result, Response::HTTP_CREATED);
    }

    /**
     * @Rest\Post("/survey/branch/add")
     */
    public function addSurveyToBranch(Request $request): View
    {
        $content = json_decode($request->getContent(), true);
        $branch_id = $content['branch_id'];
        $survey_id = $content['survey_id'];

        $result = $this->branchService->addSurvey($branch_id, $survey_id);
    
        return View::create($result, Response::HTTP_CREATED);
    }

    /**
     * @Rest\Delete("/survey/branch/remove")
     */
    public function removeSurveyFromBranch(Request $request): View
    {
        $content = json_decode($request->getContent(), true);
        $branch_id = $content['branch_id'];
        $survey_id = $content['survey_id'];

        $result = $this->branchService->removeSurvey($branch_id, $survey_id);
    
        return View::create($result, Response::HTTP_CREATED);
    }

    /**
     * @Rest\Put("/survey/branch/edit")
     */
    public function editBranch(Request $request): View
    {
        $content = json_decode($request->getContent(), true);
        $id = $content['id'];
        $name = $content['name'];
        
        $result = $this->branchService->editBranch($id, $name);

        return View::create($result, Response::HTTP_CREATED);
    }

    /**
     * @Rest\Get("/survey/branch")
     */
    public function getBusinessBranches(Request $request): View
    {
        $content = json_decode($request->getContent(), true);
        $id = $content['business_id'];
        $checker = $this->checkerService->businessChecker($id);

        $branches= array();
        if ($checker['status']) {
            $business = $checker['business'];
            $branches = $this->branchService->getBusinessBranches($business);
        }
        
        return View::create($branches, Response::HTTP_CREATED);
    }

    /**
     * @Rest\Delete("/survey/branch/delete")
     */
    public function deleteBranch(Request $request): View
    {
        $content = json_decode($request->getContent(), true);
        $id = $content['id'];
        $result = $this->branchService->deleteBranch($id);
        
        return View::create($result, Response::HTTP_CREATED);
    }
   
}