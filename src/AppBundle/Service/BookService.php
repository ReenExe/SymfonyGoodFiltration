<?php

namespace AppBundle\Service;

use Doctrine\Bundle\DoctrineBundle\Registry;
use Symfony\Component\HttpFoundation\Request;

class BookService
{
    private $doctrine;

    public function __construct(Registry $doctrine)
    {
        $this->doctrine = $doctrine;
    }

    /**
     * @param Request $request
     * @return array
     */
    public function getList(Request $request)
    {
        $limit = $request->query->get('limit', 100);
        $offset = $request->query->get('offset', 0);

        $books = $this->doctrine
            ->getRepository('AppBundle:Book')
            ->getList($limit, $offset);

        $result = [];
        foreach ($books as $book) {
            $result[] = [
                'title' => $book->getTitle(),
                'description' => $book->getDescription(),
                'image' => $book->getImage(),
            ];
        }

        return $result;
    }
}