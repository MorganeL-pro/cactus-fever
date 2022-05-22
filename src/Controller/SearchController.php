<?php

namespace App\Controller;

use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SearchController extends AbstractController
{
    /**
     * @Route("/search", name="search")
     */
    public function index(Request $request, ProductRepository $product): Response
    {
        $query = $request->query->get('q');
        if (isset($query) && $query != "") {
            $search = $product->findSearch($request->query->get('q'));
            if ($search != null) {
                return $this->render('search/index.html.twig', ['search' => $search]);
            } else {
                return $this->render('search/index.html.twig', ['search' => null]);
            }
        } else {
            return $this->render('search/index.html.twig');
        }
    }
}
