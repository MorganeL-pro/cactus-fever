<?php

namespace App\Controller;

use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Search form for products with call to custom request to Doctrine
 */
class SearchController extends AbstractController
{
    /**
     * Returns either null if query wasn't find in database
     * or a product that contains the query in its name
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
