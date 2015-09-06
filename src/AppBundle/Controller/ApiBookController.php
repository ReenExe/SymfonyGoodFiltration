<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Book;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
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
        $limit = $request->query->get('limit', 100);
        $offset = $request->query->get('limit', 100);

        $books = $this
            ->get('doctrine')
            ->getRepository('AppBundle:Book')
            ->findBy([], [], $limit, $offset);

        $result = [];
        /* @var $book Book */
        foreach ($books as $book) {
            $result[] = [
                'title' => $book->getTitle(),
                'description' => $book->getDescription(),
            ];
        }

        return new JsonResponse([
            'items' => $result
        ]);
    }
}