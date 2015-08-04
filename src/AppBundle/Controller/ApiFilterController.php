<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;

class ApiFilterController extends Controller
{
    /**
     * @Route("/api/filter/list", name="filter")
     */
    public function listAction()
    {
        return new JsonResponse();
    }
}