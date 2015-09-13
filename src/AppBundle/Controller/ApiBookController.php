<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Book;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;

class ApiBookController extends Controller
{
    /**
     * @ApiDoc(
     *  description="Get List",
     *  parameters={
     *      {"name"="limit", "dataType"="integer", "required"=false},
     *      {"name"="offset", "dataType"="integer", "required"=false}
     *  },
     *  section="books"
     * )
     */
    public function listAction(Request $request)
    {
        return new JsonResponse([
            'items' => $this->get('book_service')->getList($request)
        ]);
    }
}