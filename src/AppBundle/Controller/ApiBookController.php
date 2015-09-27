<?php

namespace AppBundle\Controller;

use FOS\RestBundle\Controller\FOSRestController;
use Symfony\Component\HttpFoundation\Request;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use FOS\RestBundle\Controller\Annotations;

class ApiBookController extends FOSRestController
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
     * @Annotations\View
     */
    public function listAction(Request $request)
    {
        return [
            'items' => $this->get('book_service')->getList($request)
        ];
    }
}